<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Customer;
use App\Models\Maintenance;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('view-reports');

        $type = in_array($request->query('type'), ['income', 'vehicles', 'customers'], true)
            ? $request->query('type')
            : 'income';

        [$from, $to, $preset] = $this->resolvePeriod($request);

        $data = match ($type) {
            'vehicles' => $this->vehicleReport($request, $from, $to),
            'customers' => $this->customerReport($request),
            default => $this->incomeReport($request, $from, $to),
        };

        return view('reports.index', array_merge($data, [
            'type' => $type,
            'preset' => $preset,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'vehicles' => Vehicle::query()->orderBy('code')->get(['id', 'code', 'brand', 'model', 'license_plate']),
            'filterVehicle' => $request->query('vehicle_id'),
            'filterStatus' => $request->query('status'),
            'filterMethod' => $request->query('method'),
            'search' => $request->query('search'),
        ]));
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function resolvePeriod(Request $request): array
    {
        $preset = $request->query('preset', 'month');

        $from = match ($preset) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'custom' => $request->filled('from') ? Carbon::parse($request->query('from'))->startOfDay() : now()->startOfMonth(),
            default => now()->startOfMonth(),
        };

        $to = match ($preset) {
            'today' => now()->endOfDay(),
            'week' => now()->endOfWeek(),
            'month' => now()->endOfMonth(),
            'custom' => $request->filled('to') ? Carbon::parse($request->query('to'))->endOfDay() : now()->endOfDay(),
            default => now()->endOfMonth(),
        };

        if ($to->lessThan($from)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to, $preset];
    }

    private function incomeReport(Request $request, Carbon $from, Carbon $to): array
    {
        $vehicleId = $request->query('vehicle_id');
        $status = $request->query('status');
        $method = $request->query('method');

        $transactionQuery = Transaction::query()
            ->whereBetween('start_at', [$from, $to])
            ->where('status', '!=', TransactionStatus::Cancelled->value)
            ->when($vehicleId, fn ($query) => $query->where('vehicle_id', $vehicleId))
            ->when($status, fn ($query) => $query->where('status', $status));

        $omzet = (clone $transactionQuery)->sum('total');
        $discountTotal = (clone $transactionQuery)->sum('discount');
        $transactionCount = (clone $transactionQuery)->count();

        $paymentQuery = Payment::query()
            ->whereBetween('paid_at', [$from->toDateString(), $to->toDateString()])
            ->when($method, fn ($query) => $query->where('method', $method))
            ->when($vehicleId || $status, fn ($query) => $query->whereHas('transaction', function ($q) use ($vehicleId, $status) {
                $q->when($vehicleId, fn ($inner) => $inner->where('vehicle_id', $vehicleId))
                    ->when($status, fn ($inner) => $inner->where('status', $status));
            }));

        $paymentsTotal = (clone $paymentQuery)->sum('amount');
        $dendaCollected = (clone $paymentQuery)->where('type', 'denda')->sum('amount');

        $outstanding = $this->outstandingFor($from, $to, $vehicleId, $status);

        $dailyPayments = Payment::query()
            ->whereBetween('paid_at', [$from->toDateString(), $to->toDateString()])
            ->when($method, fn ($query) => $query->where('method', $method))
            ->selectRaw('DATE(paid_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $details = Transaction::query()
            ->with(['customer:id,name', 'vehicle:id,code,brand,model,license_plate'])
            ->withSum('payments as paid_amount', 'amount')
            ->whereBetween('start_at', [$from, $to])
            ->where('status', '!=', TransactionStatus::Cancelled->value)
            ->when($vehicleId, fn ($query) => $query->where('vehicle_id', $vehicleId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('start_at')
            ->paginate(15)
            ->withQueryString();

        return [
            'omzet' => (int) $omzet,
            'paymentsTotal' => (int) $paymentsTotal,
            'dendaCollected' => (int) $dendaCollected,
            'discountTotal' => (int) $discountTotal,
            'transactionCount' => $transactionCount,
            'outstanding' => $outstanding,
            'dailyPayments' => $dailyPayments,
            'details' => $details,
        ];
    }

    private function outstandingFor(Carbon $from, Carbon $to, ?string $vehicleId, ?string $status): int
    {
        $where = "status != 'cancelled' AND start_at BETWEEN ? AND ?";
        $bindings = [$from->toDateTimeString(), $to->toDateTimeString()];

        if ($vehicleId !== null && $vehicleId !== '') {
            $where .= ' AND vehicle_id = ?';
            $bindings[] = (int) $vehicleId;
        }

        if ($status !== null && $status !== '') {
            $where .= ' AND status = ?';
            $bindings[] = $status;
        }

        $sql = "SELECT COALESCE(SUM(x.total + x.late_fee - COALESCE(p.paid, 0)), 0) AS outstanding
            FROM (
                SELECT id, total, late_fee FROM transactions WHERE {$where}
            ) x
            LEFT JOIN (
                SELECT transaction_id, SUM(amount) AS paid FROM payments GROUP BY transaction_id
            ) p ON p.transaction_id = x.id
            WHERE (x.total + x.late_fee - COALESCE(p.paid, 0)) > 0";

        $row = DB::selectOne($sql, $bindings);

        return (int) ($row->outstanding ?? 0);
    }

    private function vehicleReport(Request $request, Carbon $from, Carbon $to): array
    {
        $rentalStats = Transaction::query()
            ->whereBetween('start_at', [$from, $to])
            ->whereIn('status', [
                TransactionStatus::Booked->value,
                TransactionStatus::ReadyForHandover->value,
                TransactionStatus::Rented->value,
                TransactionStatus::Completed->value,
            ])
            ->selectRaw('vehicle_id, COUNT(*) as rentals, COALESCE(SUM(rental_days), 0) as days, COALESCE(SUM(total), 0) as revenue')
            ->groupBy('vehicle_id')
            ->get()
            ->keyBy('vehicle_id');

        $maintenanceStats = Maintenance::query()
            ->whereBetween('start_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('vehicle_id, COUNT(*) as total, COALESCE(SUM(cost), 0) as cost')
            ->groupBy('vehicle_id')
            ->get()
            ->keyBy('vehicle_id');

        $rows = Vehicle::query()
            ->search($request->query('search'))
            ->orderBy('code')
            ->get()
            ->map(function (Vehicle $vehicle) use ($rentalStats, $maintenanceStats) {
                $rental = $rentalStats->get($vehicle->id);
                $maintenance = $maintenanceStats->get($vehicle->id);

                return [
                    'vehicle' => $vehicle,
                    'rentals' => (int) ($rental->rentals ?? 0),
                    'days' => (int) ($rental->days ?? 0),
                    'revenue' => (int) ($rental->revenue ?? 0),
                    'maintenanceCount' => (int) ($maintenance->total ?? 0),
                    'maintenanceCost' => (int) ($maintenance->cost ?? 0),
                ];
            })
            ->sortByDesc('revenue')
            ->values();

        return [
            'vehicleRows' => $rows,
            'totalRevenue' => (int) $rows->sum('revenue'),
            'totalMaintenanceCost' => (int) $rows->sum('maintenanceCost'),
        ];
    }

    private function customerReport(Request $request): array
    {
        $customers = Customer::query()
            ->has('transactions')
            ->search($request->query('search'))
            ->withCount(['transactions', 'transactions as active_transactions' => fn ($query) => $query->whereIn('status', TransactionStatus::blocking())])
            ->withSum('transactions as total_value', 'total')
            ->withMax('transactions as last_rental', 'start_at')
            ->when($request->filled('active'), fn ($query) => $query->whereHas('transactions', fn ($q) => $q->whereIn('status', TransactionStatus::blocking())))
            ->orderByDesc('total_value')
            ->paginate(12)
            ->withQueryString();

        return ['customersReport' => $customers];
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('view-reports');

        $type = in_array($request->query('type'), ['income', 'vehicles', 'customers'], true)
            ? $request->query('type')
            : 'income';

        [$from, $to] = $this->resolvePeriod($request);
        $dateStr = $from->format('Ymd').'-sd-'.$to->format('Ymd');
        $filename = "laporan-{$type}-{$dateStr}.csv";

        return response()->streamDownload(function () use ($type, $request, $from, $to) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            if ($type === 'income') {
                fputcsv($handle, [
                    'No',
                    'No. Transaksi',
                    'Tanggal Bayar',
                    'Nama Pelanggan',
                    'No. Identitas',
                    'No. Telepon',
                    'Kendaraan',
                    'No. Polisi',
                    'Layanan',
                    'Supir',
                    'Tipe Pembayaran',
                    'Metode',
                    'Jumlah (Rp)',
                    'Pencatat',
                    'Catatan',
                ]);

                $query = Payment::query()
                    ->with(['transaction.customer', 'transaction.vehicle', 'transaction.driver', 'recorder:id,name'])
                    ->whereBetween('paid_at', [$from->toDateString(), $to->toDateString()])
                    ->orderBy('paid_at', 'desc');

                if ($request->filled('method')) {
                    $query->where('method', $request->query('method'));
                }
                if ($request->filled('vehicle_id')) {
                    $query->whereHas('transaction', fn ($q) => $q->where('vehicle_id', $request->query('vehicle_id')));
                }
                if ($request->filled('status')) {
                    $query->whereHas('transaction', fn ($q) => $q->where('status', $request->query('status')));
                }

                $no = 1;
                foreach ($query->lazy(200) as $payment) {
                    $trx = $payment->transaction;
                    fputcsv($handle, [
                        $no++,
                        $trx?->transaction_number ?? '-',
                        $payment->paid_at->format('Y-m-d'),
                        $trx?->customer?->name ?? '-',
                        $trx?->customer?->id_number ?? '-',
                        $trx?->customer?->phone ?? '-',
                        $trx?->vehicle ? ($trx->vehicle->brand.' '.$trx->vehicle->model) : '-',
                        $trx?->vehicle?->license_plate ?? '-',
                        $trx?->with_driver ? 'Dengan Supir' : 'Lepas Kunci',
                        $trx?->driver?->name ?? '-',
                        $payment->type->label(),
                        $payment->method->label(),
                        $payment->amount,
                        $payment->recorder?->name ?? 'Sistem',
                        $payment->notes ?? '-',
                    ]);
                }
            } elseif ($type === 'vehicles') {
                fputcsv($handle, [
                    'No',
                    'Kode Unit',
                    'Merk & Model',
                    'No. Polisi',
                    'Tipe',
                    'Tahun',
                    'Tarif/Hari (Rp)',
                    'Status',
                    'Total Sewa (Kali)',
                    'Total Hari Disewa',
                    'Total Pendapatan (Rp)',
                    'Jumlah Servis',
                    'Total Biaya Servis (Rp)',
                    'Laba Bersih Armada (Rp)',
                ]);

                $vehicleData = $this->vehicleReport($request, $from, $to);
                $no = 1;
                foreach ($vehicleData['vehicleRows'] as $row) {
                    $v = $row['vehicle'];
                    $profit = $row['revenue'] - $row['maintenanceCost'];
                    fputcsv($handle, [
                        $no++,
                        $v->code,
                        $v->brand.' '.$v->model,
                        $v->license_plate,
                        $v->type->label(),
                        $v->year,
                        $v->daily_rate,
                        $v->status->label(),
                        $row['rentals'],
                        $row['days'],
                        $row['revenue'],
                        $row['maintenanceCount'],
                        $row['maintenanceCost'],
                        $profit,
                    ]);
                }
            } else {
                fputcsv($handle, [
                    'No',
                    'Nama Pelanggan',
                    'No. KTP / Identitas',
                    'No. SIM',
                    'Status Verifikasi',
                    'No. Telepon',
                    'Email',
                    'Alamat',
                    'Total Transaksi Sewa',
                    'Transaksi Aktif',
                    'Total Belanja (Rp)',
                    'Rental Terakhir',
                ]);

                $customers = Customer::query()
                    ->has('transactions')
                    ->search($request->query('search'))
                    ->withCount(['transactions', 'transactions as active_transactions' => fn ($query) => $query->whereIn('status', TransactionStatus::blocking())])
                    ->withSum('transactions as total_value', 'total')
                    ->withMax('transactions as last_rental', 'start_at')
                    ->when($request->filled('active'), fn ($query) => $query->whereHas('transactions', fn ($q) => $q->whereIn('status', TransactionStatus::blocking())))
                    ->orderByDesc('total_value')
                    ->get();

                $no = 1;
                foreach ($customers as $c) {
                    fputcsv($handle, [
                        $no++,
                        $c->name,
                        $c->id_number,
                        $c->sim_number ?? '-',
                        $c->verification_status === 'verified' ? 'Terverifikasi' : ($c->verification_status === 'rejected' ? 'Ditolak' : 'Menunggu / Belum'),
                        $c->phone,
                        $c->email ?? '-',
                        $c->address ?? '-',
                        $c->transactions_count,
                        $c->active_transactions,
                        $c->total_value ?? 0,
                        $c->last_rental ? tanggal($c->last_rental) : '-',
                    ]);
                }
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

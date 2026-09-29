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
            'vehicles' => $this->vehicleReport($request),
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

    private function vehicleReport(Request $request): array
    {
        $rentalStats = Transaction::query()
            ->selectRaw('vehicle_id, COUNT(*) as rentals, COALESCE(SUM(rental_days), 0) as days, COALESCE(SUM(total), 0) as revenue')
            ->whereIn('status', [
                TransactionStatus::Booked->value,
                TransactionStatus::ReadyForHandover->value,
                TransactionStatus::Rented->value,
                TransactionStatus::Completed->value,
            ])
            ->groupBy('vehicle_id')
            ->get()
            ->keyBy('vehicle_id');

        $maintenanceStats = Maintenance::query()
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
}

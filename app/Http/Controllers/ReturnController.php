<?php

namespace App\Http\Controllers;

use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\TireCondition;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Http\Requests\ReturnRequest;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\ReturnService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function __construct(private ReturnService $returns) {}

    public function index(Request $request): View
    {
        $transactions = Transaction::query()
            ->with(['customer:id,name,phone', 'vehicle:id,code,brand,model,license_plate'])
            ->where('status', TransactionStatus::Rented->value)
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim((string) $request->query('search'));
                $query->where(function ($builder) use ($term) {
                    $builder->where('transaction_number', 'like', "%{$term}%")
                        ->orWhereHas('customer', fn ($b) => $b->where('name', 'like', "%{$term}%"))
                        ->orWhereHas('vehicle', fn ($b) => $b->where('license_plate', 'like', "%{$term}%"));
                });
            })
            ->orderBy('end_at')
            ->paginate(12)
            ->withQueryString();

        return view('return.index', [
            'transactions' => $transactions,
            'search' => $request->query('search'),
            'overdueCount' => Transaction::where('status', TransactionStatus::Rented->value)->where('end_at', '<', now())->count(),
            'dueTodayCount' => Transaction::where('status', TransactionStatus::Rented->value)->whereDate('end_at', today())->count(),
        ]);
    }

    public function create(Transaction $transaction): View
    {
        abort_unless($transaction->canBeReturned(), 403, 'Transaksi ini tidak dalam status sedang disewa.');

        $transaction->load(['customer', 'vehicle', 'handoverInspection']);

        $values = array_merge(Setting::defaults(), Setting::query()->pluck('value', 'key')->all());

        return view('return.form', [
            'transaction' => $transaction,
            'fuelLevels' => FuelLevel::options(),
            'conditions' => ConditionLevel::options(),
            'tires' => TireCondition::options(),
            'completeness' => Completeness::options(),
            'nextStatuses' => [
                VehicleStatus::Available->value => VehicleStatus::Available->label().' — langsung siap disewakan',
                VehicleStatus::Cleaning->value => VehicleStatus::Cleaning->label().' — perlu dibersihkan dulu',
                VehicleStatus::Maintenance->value => VehicleStatus::Maintenance->label().' — ada kerusakan, buat catatan perawatan',
            ],
            'lateFee' => [
                'grace' => (int) ($values['late_fee_grace_minutes'] ?? 0),
                'mode' => $values['late_fee_mode'] ?? 'per_hour',
                'rate' => (int) ($values['late_fee_rate'] ?? 0),
            ],
        ]);
    }

    public function store(ReturnRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction = $this->returns->process($transaction, $request->validated(), $request->user());

        AuditLogger::log(
            'return',
            'transactions',
            "Pengembalian kendaraan untuk transaksi {$transaction->transaction_number} diproses (denda ".rupiah($transaction->late_fee).').',
            $transaction,
        );

        $message = "Pengembalian berhasil. Transaksi {$transaction->transaction_number} selesai.";
        if ($transaction->late_fee > 0) {
            $message .= ' Denda keterlambatan '.rupiah($transaction->late_fee).' menunggu pencatatan pembayaran.';
        }

        return redirect()->route('transactions.show', $transaction)->with('success', $message);
    }
}

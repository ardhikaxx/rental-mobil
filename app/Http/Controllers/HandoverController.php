<?php

namespace App\Http\Controllers;

use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\InspectionType;
use App\Enums\TireCondition;
use App\Enums\TransactionStatus;
use App\Http\Requests\HandoverRequest;
use App\Models\Inspection;
use App\Models\Transaction;
use App\Services\HandoverService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HandoverController extends Controller
{
    public function __construct(private HandoverService $handovers) {}

    public function index(Request $request): View
    {
        $transactions = Transaction::query()
            ->with(['customer:id,name,phone', 'vehicle:id,code,brand,model,license_plate'])
            ->whereIn('status', [TransactionStatus::Booked->value, TransactionStatus::ReadyForHandover->value])
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim((string) $request->query('search'));
                $query->where(function ($builder) use ($term) {
                    $builder->where('transaction_number', 'like', "%{$term}%")
                        ->orWhereHas('customer', fn ($b) => $b->where('name', 'like', "%{$term}%"))
                        ->orWhereHas('vehicle', fn ($b) => $b->where('license_plate', 'like', "%{$term}%"));
                });
            })
            ->orderBy('start_at')
            ->paginate(12)
            ->withQueryString();

        $readyCount = Transaction::where('status', TransactionStatus::ReadyForHandover->value)->count();
        $bookedCount = Transaction::where('status', TransactionStatus::Booked->value)->count();
        $inspectedIds = Inspection::where('type', InspectionType::Handover->value)->pluck('transaction_id');

        return view('handover.index', [
            'transactions' => $transactions,
            'search' => $request->query('search'),
            'readyCount' => $readyCount,
            'bookedCount' => $bookedCount,
            'inspectedIds' => $inspectedIds,
        ]);
    }

    public function create(Transaction $transaction): View
    {
        abort_unless($transaction->canBeHandedOver(), 403, 'Transaksi ini tidak dalam status siap diserahkan.');

        $transaction->load(['customer', 'vehicle']);

        return view('handover.form', [
            'transaction' => $transaction,
            'fuelLevels' => FuelLevel::options(),
            'conditions' => ConditionLevel::options(),
            'tires' => TireCondition::options(),
            'completeness' => Completeness::options(),
        ]);
    }

    public function store(HandoverRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction = $this->handovers->process($transaction, $request->validated(), $request->user());

        AuditLogger::log(
            'handover',
            'transactions',
            "Serah terima kendaraan untuk transaksi {$transaction->transaction_number} dilakukan.",
            $transaction,
        );

        return redirect()->route('transactions.show', $transaction)
            ->with('success', "Serah terima berhasil. Transaksi {$transaction->transaction_number} kini berstatus Sedang Disewa.");
    }
}

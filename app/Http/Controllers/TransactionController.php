<?php

namespace App\Http\Controllers;

use App\Enums\BookingSource;
use App\Enums\MaintenanceStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Enums\TransactionStatus;
use App\Enums\UserRole;
use App\Enums\VehicleStatus;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Services\TransactionService;
use App\Services\VehicleAvailabilityService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactions,
        private VehicleAvailabilityService $availability,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $payment = $request->query('payment');

        $query = Transaction::query()
            ->with(['customer:id,name', 'vehicle:id,code,brand,model,license_plate'])
            ->withSum('payments as paid_amount', 'amount');

        if ($search !== null && trim($search) !== '') {
            $term = trim($search);
            $query->where(function ($builder) use ($term) {
                $builder->where('transaction_number', 'like', "%{$term}%")
                    ->orWhereHas('customer', fn ($b) => $b->where('name', 'like', "%{$term}%"))
                    ->orWhereHas('customer', fn ($b) => $b->where('phone', 'like', "%{$term}%"))
                    ->orWhereHas('vehicle', fn ($b) => $b->where('license_plate', 'like', "%{$term}%"))
                    ->orWhereHas('vehicle', fn ($b) => $b->where('code', 'like', "%{$term}%"));
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        if ($request->filled('from')) {
            $query->where('start_at', '>=', Carbon::parse($request->query('from'))->startOfDay());
        }

        if ($request->filled('to')) {
            $query->where('start_at', '<=', Carbon::parse($request->query('to'))->endOfDay());
        }

        if ($payment === 'unpaid') {
            $query->where('status', '!=', TransactionStatus::Cancelled->value)
                ->whereRaw('(transactions.total + transactions.late_fee - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.transaction_id = transactions.id), 0)) > 0');
        } elseif ($payment === 'paid') {
            $query->where('status', '!=', TransactionStatus::Cancelled->value)
                ->whereRaw('(transactions.total + transactions.late_fee - COALESCE((SELECT SUM(amount) FROM payments WHERE payments.transaction_id = transactions.id), 0)) <= 0');
        }

        $sort = $request->query('sort', 'newest');
        $sortMap = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'start_desc' => ['start_at', 'desc'],
            'start_asc' => ['start_at', 'asc'],
            'total_desc' => ['total', 'desc'],
        ];
        [$column, $direction] = $sortMap[$sort] ?? $sortMap['newest'];

        $transactions = $query->orderBy($column, $direction)->paginate(12)->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'statuses' => TransactionStatus::filterOptions(),
            'search' => $search,
            'filterStatus' => $status,
            'filterPayment' => $payment,
            'filterFrom' => $request->query('from'),
            'filterTo' => $request->query('to'),
            'sort' => $sort,
        ]);
    }

    public function create(Request $request): View
    {
        $candidates = Vehicle::query()
            ->active()
            ->where('status', '!=', VehicleStatus::Unavailable->value)
            ->whereDoesntHave('maintenances', fn ($query) => $query->whereIn('status', MaintenanceStatus::active()))
            ->orderBy('code')
            ->get();

        $periodStart = $request->filled('start_at') ? Carbon::parse($request->query('start_at')) : null;
        $periodEnd = $request->filled('end_at') ? Carbon::parse($request->query('end_at')) : null;

        $blockedIds = [];
        if ($periodStart !== null && $periodEnd !== null && $periodEnd->greaterThan($periodStart)) {
            $blockedIds = $this->availability->blockedVehicleIds($periodStart, $periodEnd);
            $candidates = $candidates->reject(fn (Vehicle $vehicle) => in_array($vehicle->id, $blockedIds, true))->values();
        }

        return view('transactions.create', [
            'customers' => Customer::query()->orderBy('name')->get(['id', 'name', 'phone', 'id_number']),
            'vehicles' => $candidates,
            'bookingSources' => BookingSource::options(),
            'paymentMethods' => PaymentMethod::options(),
            'prefill' => [
                'customer_id' => $request->query('customer_id'),
                'vehicle_id' => $request->query('vehicle_id'),
                'start_at' => $request->query('start_at'),
                'end_at' => $request->query('end_at'),
            ],
        ]);
    }

    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $transaction = $this->transactions->create($request->validated(), $request->user());

        AuditLogger::log(
            'create',
            'transactions',
            "Transaksi {$transaction->transaction_number} dibuat ({$transaction->status->label()}).",
            $transaction,
        );

        return redirect()->route('transactions.show', $transaction)
            ->with('success', "Transaksi {$transaction->transaction_number} berhasil dibuat.");
    }

    public function show(Request $request, Transaction $transaction): View
    {
        $transaction->load([
            'customer',
            'vehicle',
            'creator:id,name',
            'handoverUser:id,name',
            'returnUser:id,name',
            'payments.recorder:id,name',
            'logs.user:id,name',
            'handoverInspection.inspector:id,name',
            'handoverInspection.photos',
            'returnInspection.inspector:id,name',
            'returnInspection.photos',
        ]);
        $transaction->setAttribute('paid_amount', $transaction->paidAmount());

        $user = $request->user();

        $canManage = $user->hasRole(UserRole::SuperAdmin, UserRole::Admin);
        $canProcess = $user->hasRole(UserRole::SuperAdmin, UserRole::Admin, UserRole::Staff);

        $siblingConflicts = $transaction->isCancelable()
            ? collect()
            : $this->availability->conflicts(
                (int) $transaction->vehicle_id,
                $transaction->start_at,
                $transaction->end_at,
                $transaction->id,
            );

        return view('transactions.show', [
            'transaction' => $transaction,
            'canManage' => $canManage,
            'canProcess' => $canProcess,
            'canApprove' => $canManage && in_array($transaction->status, [TransactionStatus::Draft, TransactionStatus::AwaitingPayment], true),
            'canCancel' => $canManage && $transaction->isCancelable(),
            'canMarkReady' => $canManage && $transaction->status === TransactionStatus::Booked,
            'canHandover' => $canProcess && $transaction->canBeHandedOver(),
            'canReturn' => $canProcess && $transaction->canBeReturned(),
            'canEdit' => $canManage && in_array($transaction->status, [TransactionStatus::Draft, TransactionStatus::AwaitingPayment], true),
            'canPay' => $canManage && in_array($transaction->status->value, TransactionStatus::payable(), true) && $transaction->balance() > 0,
            'paymentMethods' => PaymentMethod::options(),
            'paymentTypes' => PaymentType::options(),
            'siblingConflicts' => $siblingConflicts,
        ]);
    }

    public function edit(Transaction $transaction): View
    {
        if (! in_array($transaction->status, [TransactionStatus::Draft, TransactionStatus::AwaitingPayment], true)) {
            throw ValidationException::withMessages([
                'status' => 'Hanya transaksi Draft atau Menunggu Pembayaran yang dapat diubah.',
            ]);
        }

        $transaction->load('customer', 'vehicle');

        return view('transactions.edit', [
            'transaction' => $transaction,
            'bookingSources' => BookingSource::options(),
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction = $this->transactions->update($transaction, $request->validated(), $request->user());

        AuditLogger::log('update', 'transactions', "Detail transaksi {$transaction->transaction_number} diubah.", $transaction);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', "Transaksi {$transaction->transaction_number} berhasil diperbarui.");
    }

    public function approve(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('manage-transactions');
        $transaction = $this->transactions->approve($transaction, $request->user());

        AuditLogger::log('status_change', 'transactions', "Transaksi {$transaction->transaction_number} disetujui (booking aktif).", $transaction);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', "Transaksi {$transaction->transaction_number} disetujui. Kendaraan dibooking untuk periode tersebut.");
    }

    public function markReady(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('manage-transactions');
        $transaction = $this->transactions->markReadyForHandover($transaction, $request->user());

        AuditLogger::log('status_change', 'transactions', "Transaksi {$transaction->transaction_number} disiapkan untuk serah terima.", $transaction);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', "Transaksi {$transaction->transaction_number} siap diserahkan.");
    }

    public function cancel(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('manage-transactions');

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ], [], ['reason' => 'alasan']);

        $transaction = $this->transactions->cancel($transaction, $request->user(), $request->input('reason'));

        AuditLogger::log('status_change', 'transactions', "Transaksi {$transaction->transaction_number} dibatalkan.", $transaction);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', "Transaksi {$transaction->transaction_number} dibatalkan.");
    }

    public function invoice(Transaction $transaction): View
    {
        $transaction->load([
            'customer',
            'vehicle',
            'creator:id,name',
            'payments.recorder:id,name',
        ]);
        $transaction->setAttribute('paid_amount', $transaction->paidAmount());

        return view('transactions.invoice', [
            'transaction' => $transaction,
            'settings' => Setting::defaults(),
            'values' => array_merge(
                Setting::defaults(),
                Setting::query()->pluck('value', 'key')->all(),
            ),
        ]);
    }
}

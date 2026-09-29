<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Enums\TransactionStatus;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\PaymentService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $payments) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = Payment::query()
            ->with(['transaction.customer', 'transaction.vehicle', 'recorder:id,name']);

        if ($search !== null && trim($search) !== '') {
            $term = trim($search);
            $query->where(function ($builder) use ($term) {
                $builder->where('payment_number', 'like', "%{$term}%")
                    ->orWhereHas('transaction', fn ($b) => $b->where('transaction_number', 'like', "%{$term}%"))
                    ->orWhereHas('transaction.customer', fn ($b) => $b->where('name', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('method')) {
            $query->where('method', $request->query('method'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        if ($request->filled('from')) {
            $query->where('paid_at', '>=', Carbon::parse($request->query('from'))->toDateString());
        }

        if ($request->filled('to')) {
            $query->where('paid_at', '<=', Carbon::parse($request->query('to'))->toDateString());
        }

        $totalFiltered = (clone $query)->sum('amount');

        $payments = $query->latest('paid_at')->latest()->paginate(12)->withQueryString();

        return view('payments.index', [
            'payments' => $payments,
            'totalFiltered' => (int) $totalFiltered,
            'methods' => PaymentMethod::options(),
            'types' => PaymentType::options(),
            'search' => $search,
            'filterMethod' => $request->query('method'),
            'filterType' => $request->query('type'),
            'filterFrom' => $request->query('from'),
            'filterTo' => $request->query('to'),
        ]);
    }

    public function create(Request $request): View
    {
        $payable = Transaction::query()
            ->with(['customer:id,name', 'vehicle:id,code,brand,model,license_plate'])
            ->withSum('payments as paid_amount', 'amount')
            ->whereIn('status', TransactionStatus::payable())
            ->orderBy('created_at', 'desc')
            ->limit(300)
            ->get()
            ->filter(fn (Transaction $transaction) => $transaction->balance() > 0)
            ->values();

        return view('payments.create', [
            'transactions' => $payable,
            'methods' => PaymentMethod::options(),
            'types' => PaymentType::options(),
            'selectedTransaction' => $request->query('transaction_id'),
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $transaction = Transaction::findOrFail($request->validated()['transaction_id']);

        $payment = $this->payments->record($transaction, $request->validated(), $request->user());

        AuditLogger::log(
            'payment',
            'payments',
            "Pembayaran {$payment->payment_number} sebesar ".rupiah($payment->amount).' dicatat untuk transaksi '.$transaction->transaction_number.'.',
            $payment,
        );

        return redirect()->route('transactions.show', $transaction)
            ->with('success', "Pembayaran {$payment->payment_number} sebesar ".rupiah($payment->amount).' berhasil dicatat.');
    }
}

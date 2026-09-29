<?php

namespace App\Services;

use App\Enums\PaymentType;
use App\Enums\TransactionStatus;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(private NumberGenerator $numbers) {}

    /**
     * Record a payment against a transaction. Enforces that the amount never
     * exceeds the remaining balance (rental total + late fee - paid).
     *
     * @param  array<string, mixed>  $data
     */
    public function record(Transaction $transaction, array $data, User $user, bool $logTimeline = true): Payment
    {
        return DB::transaction(function () use ($transaction, $data, $user, $logTimeline) {
            /** @var Transaction $transaction */
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if (! in_array($transaction->status->value, TransactionStatus::payable(), true)) {
                throw ValidationException::withMessages([
                    'status' => 'Transaksi berstatus '.$transaction->status->label().' tidak dapat menerima pembayaran.',
                ]);
            }

            $paid = (int) $transaction->payments()->sum('amount');
            $balance = $transaction->totalPayable() - $paid;
            $amount = (int) ($data['amount'] ?? 0);

            if ($balance <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Transaksi sudah lunas dan tidak memiliki sisa tagihan.',
                ]);
            }

            if ($amount > $balance) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal pembayaran melebihi sisa tagihan ('.rupiah($balance).').',
                ]);
            }

            $payment = Payment::create([
                'payment_number' => $this->numbers->paymentNumber(),
                'transaction_id' => $transaction->id,
                'amount' => $amount,
                'method' => $data['method'],
                'type' => $data['type'],
                'paid_at' => $data['paid_at'],
                'notes' => $data['notes'] ?? null,
                'recorded_by' => $user->id,
            ]);

            $newPaid = $paid + $amount;
            $newBalance = max(0, $transaction->totalPayable() - $newPaid);

            if ($logTimeline) {
                $transaction->logs()->create([
                    'user_id' => $user->id,
                    'action' => 'payment',
                    'description' => sprintf(
                        '%s %s sebesar %s dicatat oleh %s. Total tercatat %s, sisa %s.',
                        PaymentType::from($data['type'])->label(),
                        $payment->payment_number,
                        rupiah($amount),
                        $user->name,
                        rupiah($newPaid),
                        rupiah($newBalance),
                    ),
                ]);
            }

            return $payment;
        });
    }
}

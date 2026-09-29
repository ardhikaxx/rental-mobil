<?php

namespace App\Services;

use App\Enums\PaymentType;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    public function __construct(
        private VehicleAvailabilityService $availability,
        private NumberGenerator $numbers,
        private PaymentService $payments,
    ) {}

    /**
     * Create a new rental transaction (optionally as a draft) together with the
     * initial DP payment, with the vehicle row locked to avoid double booking
     * when two operators work concurrently.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, User $user): Transaction
    {
        return DB::transaction(function () use ($data, $user) {
            $vehicle = Vehicle::lockForUpdate()->findOrFail($data['vehicle_id']);

            $start = Carbon::parse($data['start_at']);
            $end = Carbon::parse($data['end_at']);

            $this->availability->assertAvailable($vehicle, $start, $end);

            $customer = Customer::findOrFail($data['customer_id']);
            $isDraft = (string) ($data['save_as_draft'] ?? '') === '1';

            $days = $this->rentalDays($start, $end);

            $withDriver = ! empty($data['with_driver']);
            $driverId = $withDriver && ! empty($data['driver_id']) ? (int) $data['driver_id'] : null;
            $driver = $driverId ? Driver::find($driverId) : null;
            $driverRate = $driver ? (int) $driver->daily_rate : 0;
            $driverFee = $withDriver ? ($driverRate * $days) : 0;

            $subtotal = ($days * (int) $vehicle->daily_rate) + $driverFee;
            $discount = (int) ($data['discount'] ?? 0);
            $total = max(0, $subtotal - $discount);

            $depositAmount = (int) ($data['deposit_amount'] ?? 0);
            $depositType = ! empty($data['deposit_type']) ? $data['deposit_type'] : null;
            $depositNotes = $data['deposit_notes'] ?? null;
            $depositStatus = ($depositAmount > 0 || ! empty($depositNotes)) ? 'pending' : 'none';

            $transaction = Transaction::create([
                'transaction_number' => $this->numbers->transactionNumber(),
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'created_by' => $user->id,
                'booking_source' => $data['booking_source'],
                'start_at' => $start,
                'end_at' => $end,
                'with_driver' => $withDriver,
                'driver_id' => $driverId,
                'driver_rate' => $driverRate,
                'driver_fee' => $driverFee,
                'deposit_type' => $depositType,
                'deposit_amount' => $depositAmount,
                'deposit_status' => $depositStatus,
                'deposit_notes' => $depositNotes,
                'daily_rate' => $vehicle->daily_rate,
                'rental_days' => $days,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => $isDraft ? TransactionStatus::Draft : TransactionStatus::AwaitingPayment,
                'notes' => $data['notes'] ?? null,
            ]);

            $dp = (int) ($data['dp_amount'] ?? 0);

            if (! $isDraft && $dp > 0) {
                $this->payments->record($transaction, [
                    'amount' => $dp,
                    'method' => $data['dp_method'] ?? 'cash',
                    'type' => PaymentType::DownPayment,
                    'paid_at' => now()->toDateString(),
                    'notes' => 'Pembayaran DP pada saat pembuatan transaksi.',
                ], $user, logTimeline: false);
            }

            $transaction->logs()->create([
                'user_id' => $user->id,
                'action' => 'created',
                'to_status' => $transaction->status->value,
                'description' => "Transaksi dibuat oleh {$user->name}.",
            ]);

            return $transaction;
        });
    }

    /**
     * Edit a draft / unpaid transaction: period, discount, source, notes.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Transaction $transaction, array $data, User $user): Transaction
    {
        return DB::transaction(function () use ($transaction, $data, $user) {
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if (! in_array($transaction->status, [TransactionStatus::Draft, TransactionStatus::AwaitingPayment], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya transaksi berstatus Draft atau Menunggu Pembayaran yang dapat diubah. Transaksi lain hanya dapat berubah melalui aksi bisnis (persetujuan, serah terima, pengembalian).',
                ]);
            }

            $vehicle = Vehicle::lockForUpdate()->findOrFail($transaction->vehicle_id);
            $start = Carbon::parse($data['start_at']);
            $end = Carbon::parse($data['end_at']);

            $this->availability->assertAvailable($vehicle, $start, $end, $transaction->id);

            $days = $this->rentalDays($start, $end);

            $withDriver = ! empty($data['with_driver']);
            $driverId = $withDriver && ! empty($data['driver_id']) ? (int) $data['driver_id'] : null;
            $driver = $driverId ? Driver::find($driverId) : null;
            $driverRate = $driver ? (int) $driver->daily_rate : 0;
            $driverFee = $withDriver ? ($driverRate * $days) : 0;

            $subtotal = ($days * (int) $vehicle->daily_rate) + $driverFee;
            $discount = (int) ($data['discount'] ?? 0);
            $total = max(0, $subtotal - $discount);

            if ($total < $transaction->paidAmount()) {
                throw ValidationException::withMessages([
                    'discount' => 'Total rental tidak boleh kurang dari pembayaran yang sudah tercatat ('.rupiah($transaction->paidAmount()).'). Kurangi diskon.',
                ]);
            }

            $depositAmount = (int) ($data['deposit_amount'] ?? 0);
            $depositType = ! empty($data['deposit_type']) ? $data['deposit_type'] : null;
            $depositNotes = $data['deposit_notes'] ?? null;
            $depositStatus = ($depositAmount > 0 || ! empty($depositNotes))
                ? ($transaction->deposit_status === 'none' ? 'pending' : $transaction->deposit_status)
                : 'none';

            $previousStatus = $transaction->status->value;

            $transaction->update([
                'booking_source' => $data['booking_source'],
                'start_at' => $start,
                'end_at' => $end,
                'rental_days' => $days,
                'with_driver' => $withDriver,
                'driver_id' => $driverId,
                'driver_rate' => $driverRate,
                'driver_fee' => $driverFee,
                'deposit_type' => $depositType,
                'deposit_amount' => $depositAmount,
                'deposit_status' => $depositStatus,
                'deposit_notes' => $depositNotes,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
            ]);

            $transaction->logs()->create([
                'user_id' => $user->id,
                'action' => 'updated',
                'from_status' => $previousStatus,
                'to_status' => $previousStatus,
                'description' => "Detail transaksi diubah oleh {$user->name} (durasi {$days} hari, total ".rupiah($total).').',
            ]);

            return $transaction;
        });
    }

    /**
     * Approve a booking. Requires the minimum down payment defined in settings.
     */
    public function approve(Transaction $transaction, User $user): Transaction
    {
        return DB::transaction(function () use ($transaction, $user) {
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if (! in_array($transaction->status, [TransactionStatus::Draft, TransactionStatus::AwaitingPayment], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya transaksi Draft atau Menunggu Pembayaran yang dapat disetujui.',
                ]);
            }

            $vehicle = Vehicle::lockForUpdate()->findOrFail($transaction->vehicle_id);
            $this->availability->assertAvailable($vehicle, $transaction->start_at, $transaction->end_at, $transaction->id);

            $paid = (int) $transaction->payments()->sum('amount');
            $minPercent = Setting::getFloat('min_dp_percent', 0);
            $minRequired = (int) ceil($transaction->total * $minPercent / 100);

            if ($minRequired > 0 && $paid < $minRequired) {
                throw ValidationException::withMessages([
                    'status' => sprintf(
                        'Persetujuan memerlukan pembayaran minimal %s%% dari total (%s). Total tercatat baru %s. Catat pembayaran terlebih dahulu.',
                        rtrim(rtrim(number_format($minPercent, 2), '0'), '.'),
                        rupiah($minRequired),
                        rupiah($paid),
                    ),
                ]);
            }

            $fromStatus = $transaction->status->value;
            $transaction->update(['status' => TransactionStatus::Booked]);

            if ($vehicle->status->value === 'tersedia') {
                $vehicle->update(['status' => VehicleStatus::Booked]);
            }

            $transaction->logs()->create([
                'user_id' => $user->id,
                'action' => 'status_change',
                'from_status' => $fromStatus,
                'to_status' => TransactionStatus::Booked->value,
                'description' => "Booking disetujui oleh {$user->name}.",
            ]);

            return $transaction->refresh();
        });
    }

    /**
     * Cancel a transaction and release the vehicle when it is no longer held.
     */
    public function cancel(Transaction $transaction, User $user, ?string $reason = null): Transaction
    {
        return DB::transaction(function () use ($transaction, $user, $reason) {
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if (! $transaction->isCancelable()) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya transaksi Draft, Menunggu Pembayaran, Disetujui, atau Siap Diserahkan yang dapat dibatalkan. Transaksi yang sedang disewa harus melalui proses pengembalian.',
                ]);
            }

            $fromStatus = $transaction->status->value;
            $transaction->update(['status' => TransactionStatus::Cancelled]);

            $vehicle = Vehicle::lockForUpdate()->findOrFail($transaction->vehicle_id);

            if ($vehicle->status->value === 'dibooking') {
                $stillBooked = $vehicle->transactions()
                    ->whereKey('!=', $transaction->id)
                    ->whereIn('status', TransactionStatus::blocking())
                    ->exists();

                if (! $stillBooked) {
                    $vehicle->update(['status' => VehicleStatus::Available]);
                }
            }

            $description = "Transaksi dibatalkan oleh {$user->name}.";
            if ($reason !== null && $reason !== '') {
                $description .= ' Alasan: '.$reason;
            }

            $transaction->logs()->create([
                'user_id' => $user->id,
                'action' => 'status_change',
                'from_status' => $fromStatus,
                'to_status' => TransactionStatus::Cancelled->value,
                'description' => $description,
            ]);

            return $transaction->refresh();
        });
    }

    /**
     * Mark an approved transaction as ready to be handed over to the customer.
     */
    public function markReadyForHandover(Transaction $transaction, User $user): Transaction
    {
        return DB::transaction(function () use ($transaction, $user) {
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if ($transaction->status !== TransactionStatus::Booked) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya transaksi berstatus Disetujui yang dapat ditandai siap diserahkan.',
                ]);
            }

            $fromStatus = $transaction->status->value;
            $transaction->update(['status' => TransactionStatus::ReadyForHandover]);

            $transaction->logs()->create([
                'user_id' => $user->id,
                'action' => 'status_change',
                'from_status' => $fromStatus,
                'to_status' => TransactionStatus::ReadyForHandover->value,
                'description' => "Transaksi disiapan untuk serah terima oleh {$user->name}.",
            ]);

            return $transaction->refresh();
        });
    }

    public function rentalDays(CarbonInterface $start, CarbonInterface $end): int
    {
        return (int) max(1, ceil($start->diffInHours($end) / 24));
    }
}

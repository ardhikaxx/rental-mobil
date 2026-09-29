<?php

namespace App\Models;

use App\Enums\BookingSource;
use App\Enums\TransactionStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'customer_id',
        'vehicle_id',
        'created_by',
        'booking_source',
        'start_at',
        'end_at',
        'handover_at',
        'handed_over_by',
        'actual_return_at',
        'returned_by',
        'daily_rate',
        'rental_days',
        'subtotal',
        'discount',
        'total',
        'late_minutes',
        'late_fee',
        'status',
        'notes',
        'with_driver',
        'driver_id',
        'driver_rate',
        'driver_fee',
        'deposit_type',
        'deposit_amount',
        'deposit_status',
        'deposit_notes',
        'deposit_refunded_at',
        'deposit_refunded_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_source' => BookingSource::class,
            'status' => TransactionStatus::class,
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'handover_at' => 'datetime',
            'actual_return_at' => 'datetime',
            'daily_rate' => 'integer',
            'rental_days' => 'integer',
            'subtotal' => 'integer',
            'discount' => 'integer',
            'total' => 'integer',
            'late_minutes' => 'integer',
            'late_fee' => 'integer',
            'with_driver' => 'boolean',
            'driver_rate' => 'integer',
            'driver_fee' => 'integer',
            'deposit_amount' => 'integer',
            'deposit_refunded_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function depositRefundedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deposit_refunded_by');
    }

    public function handoverUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handed_over_by');
    }

    public function returnUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    public function handoverInspection(): HasOne
    {
        return $this->hasOne(Inspection::class)->where('type', 'handover')->latestOfMany();
    }

    public function returnInspection(): HasOne
    {
        return $this->hasOne(Inspection::class)->where('type', 'return')->latestOfMany();
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TransactionLog::class)->latest();
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', TransactionStatus::blocking());
    }

    /**
     * Total amount that must be paid for this transaction (rental total plus late fee).
     */
    public function totalPayable(): int
    {
        return (int) $this->total + (int) $this->late_fee;
    }

    /**
     * Amount already recorded as paid. Uses the aggregated attribute when the
     * relation was eager loaded with `withSum` to avoid N+1 queries.
     */
    public function paidAmount(): int
    {
        if (array_key_exists('payments_sum_amount', $this->attributes)) {
            return (int) $this->attributes['payments_sum_amount'];
        }

        if ($this->relationLoaded('payments')) {
            return (int) $this->payments->sum('amount');
        }

        return (int) $this->payments()->sum('amount');
    }

    public function balance(): int
    {
        return max(0, $this->totalPayable() - $this->paidAmount());
    }

    public function isFullyPaid(): bool
    {
        return $this->balance() <= 0;
    }

    public function isBlocking(): bool
    {
        return in_array($this->status->value, TransactionStatus::blocking(), true);
    }

    public function isActive(): bool
    {
        return in_array($this->status->value, [
            TransactionStatus::Booked->value,
            TransactionStatus::ReadyForHandover->value,
            TransactionStatus::Rented->value,
        ], true);
    }

    public function isOverdue(): bool
    {
        return $this->status === TransactionStatus::Rented
            && $this->end_at !== null
            && $this->end_at->isPast();
    }

    public function isDueSoon(): bool
    {
        return $this->status === TransactionStatus::Rented
            && $this->end_at !== null
            && $this->end_at->isFuture()
            && now()->diffInHours($this->end_at) <= 24;
    }

    public function isCancelable(): bool
    {
        return in_array($this->status->value, [
            TransactionStatus::Draft->value,
            TransactionStatus::AwaitingPayment->value,
            TransactionStatus::Booked->value,
            TransactionStatus::ReadyForHandover->value,
        ], true);
    }

    public function canBeHandedOver(): bool
    {
        return in_array($this->status->value, [
            TransactionStatus::Booked->value,
            TransactionStatus::ReadyForHandover->value,
        ], true);
    }

    public function canBeReturned(): bool
    {
        return $this->status === TransactionStatus::Rented;
    }

    /**
     * Does the given period overlap with this (blocking) transaction?
     */
    public function overlaps(CarbonInterface $start, CarbonInterface $end): bool
    {
        return $this->start_at->lt($end) && $this->end_at->gt($start);
    }
}

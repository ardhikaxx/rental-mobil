<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Draft = 'draft';
    case AwaitingPayment = 'awaiting_payment';
    case Booked = 'booked';
    case ReadyForHandover = 'ready_for_handover';
    case Rented = 'rented';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::AwaitingPayment => 'Menunggu Pembayaran',
            self::Booked => 'Disetujui',
            self::ReadyForHandover => 'Siap Diserahkan',
            self::Rented => 'Sedang Disewa',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Draft => 'fa-file-pen',
            self::AwaitingPayment => 'fa-clock',
            self::Booked => 'fa-calendar-check',
            self::ReadyForHandover => 'fa-key',
            self::Rented => 'fa-road',
            self::Completed => 'fa-circle-check',
            self::Cancelled => 'fa-circle-xmark',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Draft => 'badge-secondary',
            self::AwaitingPayment => 'badge-warning',
            self::Booked => 'badge-info',
            self::ReadyForHandover => 'badge-info',
            self::Rented => 'badge-primary',
            self::Completed => 'badge-success',
            self::Cancelled => 'badge-danger',
        };
    }

    /**
     * Statuses that block a vehicle from being booked for an overlapping period.
     *
     * @return array<int, string>
     */
    public static function blocking(): array
    {
        return [
            self::AwaitingPayment->value,
            self::Booked->value,
            self::ReadyForHandover->value,
            self::Rented->value,
        ];
    }

    /**
     * Statuses in which money can still be recorded against the transaction.
     *
     * @return array<int, string>
     */
    public static function payable(): array
    {
        return [
            self::AwaitingPayment->value,
            self::Booked->value,
            self::ReadyForHandover->value,
            self::Rented->value,
            self::Completed->value,
        ];
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    /** @return array<string, string> */
    public static function filterOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        unset($options[self::Draft->value]);

        return $options;
    }
}

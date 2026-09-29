<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case Available = 'tersedia';
    case Booked = 'dibooking';
    case Rented = 'disewa';
    case Cleaning = 'dibersihkan';
    case Ready = 'siap_jalan';
    case Maintenance = 'perawatan';
    case Unavailable = 'tidak_tersedia';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Tersedia',
            self::Booked => 'Dibooking',
            self::Rented => 'Sedang Disewa',
            self::Cleaning => 'Sedang Dibersihkan',
            self::Ready => 'Siap Jalan',
            self::Maintenance => 'Perawatan',
            self::Unavailable => 'Tidak Tersedia',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Available => 'fa-circle-check',
            self::Booked => 'fa-calendar-check',
            self::Rented => 'fa-road',
            self::Cleaning => 'fa-broom',
            self::Ready => 'fa-gauge-high',
            self::Maintenance => 'fa-wrench',
            self::Unavailable => 'fa-circle-minus',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Available => 'badge-success',
            self::Booked => 'badge-info',
            self::Rented => 'badge-primary',
            self::Cleaning => 'badge-warning',
            self::Ready => 'badge-success',
            self::Maintenance => 'badge-danger',
            self::Unavailable => 'badge-secondary',
        };
    }

    /**
     * Statuses that are considered operational (vehicle may be rented out again
     * once no active transaction conflicts with the requested period).
     *
     * @return array<int, string>
     */
    public static function rentable(): array
    {
        return [
            self::Available->value,
            self::Cleaning->value,
            self::Ready->value,
            self::Booked->value,
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
}

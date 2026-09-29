<?php

namespace App\Enums;

enum VehicleType: string
{
    case Sedan = 'sedan';
    case Hatchback = 'hatchback';
    case MPV = 'mpv';
    case SUV = 'suv';
    case Minibus = 'minibus';
    case Pickup = 'pickup';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Sedan => 'Sedan',
            self::Hatchback => 'Hatchback',
            self::MPV => 'MPV',
            self::SUV => 'SUV',
            self::Minibus => 'Minibus',
            self::Pickup => 'Pickup',
            self::Other => 'Lainnya',
        };
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

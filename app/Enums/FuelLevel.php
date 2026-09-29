<?php

namespace App\Enums;

enum FuelLevel: string
{
    case Empty = 'empty';
    case Quarter = 'quarter';
    case Half = 'half';
    case ThreeQuarters = 'three_quarters';
    case Full = 'full';

    public function label(): string
    {
        return match ($this) {
            self::Empty => 'Kosong',
            self::Quarter => '1/4',
            self::Half => '1/2',
            self::ThreeQuarters => '3/4',
            self::Full => 'Penuh',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Empty => 'fa-gas-pump',
            default => 'fa-gas-pump',
        };
    }

    public function percent(): int
    {
        return match ($this) {
            self::Empty => 0,
            self::Quarter => 25,
            self::Half => 50,
            self::ThreeQuarters => 75,
            self::Full => 100,
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

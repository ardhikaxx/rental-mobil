<?php

namespace App\Enums;

enum TireCondition: string
{
    case Good = 'good';
    case Worn = 'worn';
    case Replace = 'replace';

    public function label(): string
    {
        return match ($this) {
            self::Good => 'Baik',
            self::Worn => 'Aus',
            self::Replace => 'Harus Ganti',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Good => 'badge-success',
            self::Worn => 'badge-warning',
            self::Replace => 'badge-danger',
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

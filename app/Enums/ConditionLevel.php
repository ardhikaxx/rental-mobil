<?php

namespace App\Enums;

enum ConditionLevel: string
{
    case Good = 'good';
    case Minor = 'minor';
    case Major = 'major';

    public function label(): string
    {
        return match ($this) {
            self::Good => 'Baik',
            self::Minor => 'Rusak Ringan',
            self::Major => 'Rusak Berat',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Good => 'badge-success',
            self::Minor => 'badge-warning',
            self::Major => 'badge-danger',
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

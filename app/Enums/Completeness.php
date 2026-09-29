<?php

namespace App\Enums;

enum Completeness: string
{
    case Complete = 'lengkap';
    case Incomplete = 'kurang';

    public function label(): string
    {
        return match ($this) {
            self::Complete => 'Lengkap',
            self::Incomplete => 'Tidak Lengkap',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Complete => 'badge-success',
            self::Incomplete => 'badge-warning',
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

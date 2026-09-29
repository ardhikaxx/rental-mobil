<?php

namespace App\Enums;

enum MaintenanceType: string
{
    case Routine = 'routine';
    case Oil = 'oil';
    case Tire = 'tire';
    case Brake = 'brake';
    case Engine = 'engine';
    case Electrical = 'electrical';
    case Body = 'body';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Routine => 'Servis Rutin',
            self::Oil => 'Ganti Oli',
            self::Tire => 'Ban',
            self::Brake => 'Rem',
            self::Engine => 'Mesin',
            self::Electrical => 'Kelistrikan',
            self::Body => 'Body & Cat',
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

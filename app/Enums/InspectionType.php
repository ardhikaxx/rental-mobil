<?php

namespace App\Enums;

enum InspectionType: string
{
    case Handover = 'handover';
    case Return = 'return';
    case Routine = 'routine';

    public function label(): string
    {
        return match ($this) {
            self::Handover => 'Pemeriksaan Serah Terima',
            self::Return => 'Pemeriksaan Pengembalian',
            self::Routine => 'Pemeriksaan Rutin',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Handover => 'fa-key',
            self::Return => 'fa-rotate-left',
            self::Routine => 'fa-clipboard-check',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Handover => 'badge-info',
            self::Return => 'badge-primary',
            self::Routine => 'badge-secondary',
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

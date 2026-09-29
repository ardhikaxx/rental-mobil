<?php

namespace App\Enums;

enum BookingSource: string
{
    case WalkIn = 'walk_in';
    case WhatsApp = 'whatsapp';
    case Phone = 'phone';
    case Referral = 'referral';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WalkIn => 'Walk-in',
            self::WhatsApp => 'WhatsApp',
            self::Phone => 'Telepon',
            self::Referral => 'Referral',
            self::Other => 'Lainnya',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::WalkIn => 'fa-person-walking',
            self::WhatsApp => 'fa-brands fa-whatsapp',
            self::Phone => 'fa-phone',
            self::Referral => 'fa-user-group',
            self::Other => 'fa-ellipsis',
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

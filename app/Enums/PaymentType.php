<?php

namespace App\Enums;

enum PaymentType: string
{
    case DownPayment = 'dp';
    case Installment = 'cicilan';
    case Final = 'pelunasan';
    case LateFee = 'denda';

    public function label(): string
    {
        return match ($this) {
            self::DownPayment => 'DP',
            self::Installment => 'Cicilan',
            self::Final => 'Pelunasan',
            self::LateFee => 'Denda',
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

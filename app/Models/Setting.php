<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Retrieve a setting value with a safe default.
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        $setting = static::query()->where('key', $key)->first();

        if ($setting === null || $setting->value === null) {
            return $default;
        }

        return $setting->value;
    }

    public static function getInt(string $key, int $default = 0): int
    {
        return (int) static::getValue($key, (string) $default);
    }

    public static function getFloat(string $key, float $default = 0): float
    {
        return (float) static::getValue($key, (string) $default);
    }

    public static function put(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Business rule values used across the application.
     *
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'company_name' => 'Rental Mobil Nusantara',
            'company_address' => 'Jl. Raya Contoh No. 123, Jakarta',
            'company_phone' => '021-555-0123',
            'transaction_prefix' => 'RNT',
            'payment_prefix' => 'PAY',
            'min_dp_percent' => '20',
            'late_fee_grace_minutes' => '60',
            'late_fee_mode' => 'per_hour',
            'late_fee_rate' => '50000',
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Identitas perusahaan & aturan bisnis (ditulis eksplisit, tanpa loop).
     */
    public function run(): void
    {
        Setting::create(['key' => 'company_name', 'value' => 'Jaya Trans Rental Mobil']);
        Setting::create(['key' => 'company_address', 'value' => 'Jl. Kalimantan No. 42, Sumbersari, Jember, Jawa Timur 68121']);
        Setting::create(['key' => 'company_phone', 'value' => '0331-887412']);
        Setting::create(['key' => 'transaction_prefix', 'value' => 'RNT']);
        Setting::create(['key' => 'payment_prefix', 'value' => 'PAY']);
        Setting::create(['key' => 'min_dp_percent', 'value' => '20']);
        Setting::create(['key' => 'late_fee_grace_minutes', 'value' => '60']);
        Setting::create(['key' => 'late_fee_mode', 'value' => 'per_hour']);
        Setting::create(['key' => 'late_fee_rate', 'value' => '50000']);
    }
}

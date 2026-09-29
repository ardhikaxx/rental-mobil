<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Dataset operasional "Jaya Trans Rental Mobil" — Jember, Jawa Timur.
     *
     * Seluruh seeder ditulis eksplisit (tanpa factory, faker, random, maupun
     * looping) dan dibungkus satu database transaction sehingga seeding gagal
     * total tanpa meninggalkan data setengah jadi.
     *
     * Dataset disusun untuk tanggal operasional 29 September 2026 dan mencakup
     * histori Januari 2025 s/d September 2026. Jalankan pada database bersih:
     *   php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->call([
                SettingSeeder::class,
                UserSeeder::class,
                VehicleSeeder::class,
                CustomerSeeder::class,
                TransactionSeeder::class,
                PaymentSeeder::class,
                InspectionSeeder::class,
                MaintenanceSeeder::class,
                TransactionLogSeeder::class,
                AuditLogSeeder::class,
            ]);
        });
    }
}

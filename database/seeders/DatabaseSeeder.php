<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            VehicleSeeder::class,
            TransactionSeeder::class,
            MaintenanceSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}

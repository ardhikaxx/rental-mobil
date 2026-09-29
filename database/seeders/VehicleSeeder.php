<?php

namespace Database\Seeders;

use App\Enums\FuelLevel;
use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['code' => 'VT-001', 'brand' => 'Toyota', 'model' => 'Avanza', 'type' => 'mpv', 'year' => 2022, 'color' => 'Putih', 'license_plate' => 'B 1234 TOY', 'daily_rate' => 350000, 'odometer' => 48500],
            ['code' => 'VT-002', 'brand' => 'Toyota', 'model' => 'Innova', 'type' => 'mpv', 'year' => 2021, 'color' => 'Hitam', 'license_plate' => 'B 5678 INN', 'daily_rate' => 550000, 'odometer' => 62300],
            ['code' => 'VT-003', 'brand' => 'Daihatsu', 'model' => 'Xenia', 'type' => 'mpv', 'year' => 2023, 'color' => 'Silver', 'license_plate' => 'B 2345 XEN', 'daily_rate' => 350000, 'odometer' => 31200],
            ['code' => 'VT-004', 'brand' => 'Honda', 'model' => 'Brio', 'type' => 'hatchback', 'year' => 2022, 'color' => 'Merah', 'license_plate' => 'B 8765 BRI', 'daily_rate' => 300000, 'odometer' => 27900],
            ['code' => 'VT-005', 'brand' => 'Suzuki', 'model' => 'Ertiga', 'type' => 'mpv', 'year' => 2021, 'color' => 'Abu-abu', 'license_plate' => 'B 4321 ERT', 'daily_rate' => 400000, 'odometer' => 55400],
            ['code' => 'VT-006', 'brand' => 'Mitsubishi', 'model' => 'Xpander', 'type' => 'mpv', 'year' => 2023, 'color' => 'Putih', 'license_plate' => 'B 9876 XPA', 'daily_rate' => 450000, 'odometer' => 19800],
            ['code' => 'VT-007', 'brand' => 'Daihatsu', 'model' => 'Terios', 'type' => 'suv', 'year' => 2020, 'color' => 'Hitam', 'license_plate' => 'B 6543 TER', 'daily_rate' => 400000, 'odometer' => 71250],
            ['code' => 'VT-008', 'brand' => 'Honda', 'model' => 'Mobilio', 'type' => 'mpv', 'year' => 2022, 'color' => 'Putih', 'license_plate' => 'B 1122 MOB', 'daily_rate' => 400000, 'odometer' => 43800],
            ['code' => 'VT-009', 'brand' => 'Toyota', 'model' => 'Raize', 'type' => 'suv', 'year' => 2023, 'color' => 'Biru', 'license_plate' => 'B 3344 RAI', 'daily_rate' => 550000, 'odometer' => 15600],
            ['code' => 'VT-010', 'brand' => 'Suzuki', 'model' => 'Baleno', 'type' => 'hatchback', 'year' => 2021, 'color' => 'Silver', 'license_plate' => 'B 5566 BAL', 'daily_rate' => 300000, 'odometer' => 40200],
            ['code' => 'VT-011', 'brand' => 'Toyota', 'model' => 'Agya', 'type' => 'hatchback', 'year' => 2020, 'color' => 'Putih', 'license_plate' => 'B 7788 AGY', 'daily_rate' => 250000, 'odometer' => 68100],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::query()->firstOrCreate(
                ['code' => $vehicle['code']],
                $vehicle + [
                    'fuel_level' => FuelLevel::Full,
                    'status' => VehicleStatus::Available,
                    'is_active' => true,
                    'notes' => null,
                ],
            );
        }
    }
}

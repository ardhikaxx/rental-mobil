<?php

namespace Database\Seeders;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Enums\VehicleStatus;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('username', 'superadmin')->firstOrFail();
        $staff = User::where('username', 'stafgarasi')->firstOrFail();

        // Riwayat perawatan selesai (VT-001).
        $vehicle = Vehicle::where('code', 'VT-001')->firstOrFail();
        Maintenance::firstOrCreate(
            ['vehicle_id' => $vehicle->id, 'start_date' => now()->subDays(40)->toDateString()],
            [
                'type' => MaintenanceType::Routine->value,
                'status' => MaintenanceStatus::Completed->value,
                'end_date' => now()->subDays(38)->toDateString(),
                'odometer' => 45000,
                'description' => 'Servis rutin 40.000 km: ganti oli, filter, dan pemeriksaan rem.',
                'cost' => 850000,
                'workshop' => 'Bengkel Sejahtera Motor',
                'notes' => 'Kendaraan dalam kondisi baik setelah servis.',
                'recorded_by' => $owner->id,
            ],
        );

        // Perawatan berjalan (VT-010) — kendaraan berstatus perawatan.
        $vehicle = Vehicle::where('code', 'VT-010')->firstOrFail();
        $active = Maintenance::firstOrCreate(
            ['vehicle_id' => $vehicle->id, 'start_date' => now()->subDays(2)->toDateString()],
            [
                'type' => MaintenanceType::Brake->value,
                'status' => MaintenanceStatus::InProgress->value,
                'end_date' => null,
                'odometer' => 40200,
                'description' => 'Ganti kampas rem depan dan pemeriksaan sistem pengereman.',
                'cost' => 0,
                'workshop' => 'Bengkel Sejahtera Motor',
                'notes' => 'Menunggu suku cadang.',
                'recorded_by' => $staff->id,
            ],
        );
        $vehicle->update(['status' => VehicleStatus::Maintenance]);

        // Perawatan terjadwal (VT-002).
        $vehicle = Vehicle::where('code', 'VT-002')->firstOrFail();
        Maintenance::firstOrCreate(
            ['vehicle_id' => $vehicle->id, 'start_date' => now()->addDays(3)->toDateString()],
            [
                'type' => MaintenanceType::Oil->value,
                'status' => MaintenanceStatus::Scheduled->value,
                'end_date' => null,
                'odometer' => 62500,
                'description' => 'Ganti oli mesin dan filter udara terjadwal.',
                'cost' => 0,
                'workshop' => 'Bengkel Sejahtera Motor',
                'notes' => null,
                'recorded_by' => $owner->id,
            ],
        );
    }
}

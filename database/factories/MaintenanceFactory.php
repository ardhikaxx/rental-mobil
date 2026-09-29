<?php

namespace Database\Factories;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Maintenance>
 */
class MaintenanceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'type' => fake()->randomElement(MaintenanceType::cases())->value,
            'status' => MaintenanceStatus::Completed,
            'start_date' => now()->subDays(20)->toDateString(),
            'end_date' => now()->subDays(18)->toDateString(),
            'odometer' => fake()->numberBetween(10000, 90000),
            'description' => fake()->sentence(),
            'cost' => fake()->randomElement([150000, 350000, 750000, 1500000]),
            'workshop' => fake()->company(),
            'notes' => null,
            'recorded_by' => null,
        ];
    }
}

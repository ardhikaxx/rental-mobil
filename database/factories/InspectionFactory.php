<?php

namespace Database\Factories;

use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\InspectionType;
use App\Enums\TireCondition;
use App\Models\Inspection;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inspection>
 */
class InspectionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'transaction_id' => null,
            'type' => InspectionType::Routine,
            'inspected_at' => now(),
            'inspected_by' => null,
            'odometer' => fake()->numberBetween(10000, 90000),
            'fuel_level' => fake()->randomElement(FuelLevel::cases())->value,
            'exterior_condition' => ConditionLevel::Good,
            'interior_condition' => ConditionLevel::Good,
            'tire_condition' => TireCondition::Good,
            'completeness' => 'lengkap',
            'missing_items' => null,
            'existing_damage' => null,
            'new_damage' => null,
            'notes' => null,
            'vehicle_status_after' => null,
        ];
    }
}

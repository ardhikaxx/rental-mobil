<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Driver>
 */
class DriverFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'DRV-'.fake()->unique()->numerify('####'),
            'name' => fake()->name(),
            'phone' => fake()->unique()->numerify('08##########'),
            'sim_number' => fake()->numerify('####-####-######'),
            'sim_type' => fake()->randomElement(['SIM A', 'SIM B1', 'SIM B1 Umum']),
            'daily_rate' => 150000,
            'status' => 'available',
            'photo' => null,
            'notes' => null,
            'is_active' => true,
        ];
    }

    public function busy(): static
    {
        return $this->state(fn () => [
            'status' => 'busy',
        ]);
    }

    public function off(): static
    {
        return $this->state(fn () => [
            'status' => 'off',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
            'status' => 'inactive',
        ]);
    }
}

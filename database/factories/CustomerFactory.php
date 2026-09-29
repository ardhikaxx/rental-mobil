<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'id_number' => fake()->unique()->numerify('################'),
            'phone' => fake()->unique()->numerify('08##########'),
            'email' => fake()->boolean(60) ? fake()->unique()->safeEmail() : null,
            'address' => fake()->address(),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
            'notes' => null,
        ];
    }
}

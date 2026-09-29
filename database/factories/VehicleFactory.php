<?php

namespace Database\Factories;

use App\Enums\FuelLevel;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = [
            'Toyota' => ['Avanza', 'Innova', 'Raize', 'Agya'],
            'Daihatsu' => ['Xenia', 'Terios', 'Ayla'],
            'Honda' => ['Brio', 'Mobilio', 'BR-V'],
            'Suzuki' => ['Ertiga', 'Baleno', 'Carry'],
            'Mitsubishi' => ['Xpander', 'Pajero Sport'],
        ];

        $brand = fake()->randomElement(array_keys($brands));
        $prefix = fake()->unique()->randomNumber(3);

        return [
            'code' => 'VT-'.$prefix,
            'brand' => $brand,
            'model' => fake()->randomElement($brands[$brand]),
            'type' => fake()->randomElement(VehicleType::cases())->value,
            'year' => fake()->numberBetween(2018, 2025),
            'color' => fake()->randomElement(['Putih', 'Hitam', 'Silver', 'Abu-abu', 'Merah', 'Biru']),
            'license_plate' => strtoupper(fake()->unique()->bothify('? ### ??')),
            'chassis_number' => strtoupper(fake()->unique()->bothify('################')),
            'engine_number' => strtoupper(fake()->unique()->bothify('############')),
            'daily_rate' => fake()->randomElement([250000, 300000, 350000, 400000, 450000, 550000, 700000]),
            'photo' => null,
            'status' => VehicleStatus::Available,
            'odometer' => fake()->numberBetween(10000, 90000),
            'fuel_level' => fake()->randomElement(FuelLevel::cases())->value,
            'notes' => null,
            'is_active' => true,
        ];
    }

    public function status(VehicleStatus $status): static
    {
        return $this->state(fn () => ['status' => $status]);
    }
}

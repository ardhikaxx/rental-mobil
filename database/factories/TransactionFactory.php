<?php

namespace Database\Factories;

use App\Enums\BookingSource;
use App\Enums\TransactionStatus;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now()->addDays(2)->setTime(9, 0);
        $end = $start->copy()->addDays(3)->setTime(17, 0);
        $dailyRate = fake()->randomElement([300000, 350000, 400000]);
        $days = max(1, (int) ceil($start->diffInHours($end) / 24));
        $subtotal = $days * $dailyRate;

        return [
            'transaction_number' => 'RNT-TEST-'.fake()->unique()->numberBetween(1, 999999),
            'customer_id' => Customer::factory(),
            'vehicle_id' => Vehicle::factory(),
            'created_by' => null,
            'booking_source' => fake()->randomElement(BookingSource::cases())->value,
            'start_at' => $start,
            'end_at' => $end,
            'daily_rate' => $dailyRate,
            'rental_days' => $days,
            'subtotal' => $subtotal,
            'discount' => 0,
            'total' => $subtotal,
            'late_minutes' => 0,
            'late_fee' => 0,
            'status' => TransactionStatus::AwaitingPayment,
            'notes' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => TransactionStatus::Draft]);
    }

    public function booked(): static
    {
        return $this->state(fn () => ['status' => TransactionStatus::Booked]);
    }

    public function rented(): static
    {
        return $this->state(function () {
            $start = now()->subDays(2)->setTime(9, 0);
            $end = $start->copy()->addDays(4)->setTime(17, 0);
            $days = max(1, (int) ceil($start->diffInHours($end) / 24));

            return [
                'status' => TransactionStatus::Rented,
                'start_at' => $start,
                'end_at' => $end,
                'handover_at' => $start,
                'rental_days' => $days,
                'subtotal' => $days * 350000,
                'total' => $days * 350000,
                'daily_rate' => 350000,
            ];
        });
    }

    public function completed(): static
    {
        return $this->state(function () {
            $start = now()->subDays(10)->setTime(9, 0);
            $end = $start->copy()->addDays(3)->setTime(17, 0);
            $days = max(1, (int) ceil($start->diffInHours($end) / 24));

            return [
                'status' => TransactionStatus::Completed,
                'start_at' => $start,
                'end_at' => $end,
                'handover_at' => $start,
                'actual_return_at' => $end,
                'rental_days' => $days,
                'subtotal' => $days * 350000,
                'total' => $days * 350000,
                'daily_rate' => 350000,
            ];
        });
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => TransactionStatus::Cancelled]);
    }
}

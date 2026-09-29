<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_number' => 'PAY-TEST-'.fake()->unique()->numberBetween(1, 999999),
            'transaction_id' => Transaction::factory(),
            'amount' => fake()->randomElement([100000, 200000, 500000, 1000000]),
            'method' => fake()->randomElement(PaymentMethod::cases())->value,
            'type' => PaymentType::DownPayment,
            'paid_at' => now()->toDateString(),
            'notes' => null,
            'recorded_by' => null,
        ];
    }
}

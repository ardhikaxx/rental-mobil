<?php

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;

function payableTransaction(int $total): Transaction
{
    return Transaction::factory()->create([
        'vehicle_id' => Vehicle::factory()->create()->id,
        'customer_id' => Customer::factory()->create()->id,
        'daily_rate' => $total,
        'rental_days' => 1,
        'subtotal' => $total,
        'total' => $total,
        'start_at' => now()->addDays(2)->setTime(9, 0),
        'end_at' => now()->addDays(3)->setTime(9, 0),
    ]);
}

it('records a payment and keeps the running balance accurate', function () {
    $transaction = payableTransaction(500000);
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('payments.store'), [
        'transaction_id' => $transaction->id,
        'amount' => 200000,
        'method' => 'cash',
        'type' => 'dp',
        'paid_at' => now()->toDateString(),
        'notes' => 'DP.',
    ]);

    $response->assertSessionHasNoErrors();
    $payment = $transaction->payments()->first();

    expect($payment->payment_number)->toStartWith('PAY-')
        ->and($payment->amount)->toBe(200000)
        ->and((int) $transaction->payments()->sum('amount'))->toBe(200000);
});

it('rejects a payment exceeding the remaining balance', function () {
    $transaction = payableTransaction(500000);

    $transaction->payments()->create([
        'payment_number' => 'PAY-TEST-1',
        'amount' => 400000,
        'method' => 'cash',
        'type' => 'dp',
        'paid_at' => now()->toDateString(),
    ]);

    $response = $this->actingAs(User::factory()->admin()->create())->post(route('payments.store'), [
        'transaction_id' => $transaction->id,
        'amount' => 200000,
        'method' => 'cash',
        'type' => 'cicilan',
        'paid_at' => now()->toDateString(),
    ]);

    $response->assertSessionHasErrors('amount');
    expect($transaction->payments()->sum('amount'))->toBe(400000);
});

it('includes the late fee in the payable balance after return', function () {
    $transaction = payableTransaction(500000);
    $transaction->update(['late_fee' => 100000]);

    $this->actingAs(User::factory()->admin()->create())
        ->post(route('payments.store'), [
            'transaction_id' => $transaction->id,
            'amount' => 600000,
            'method' => 'cash',
            'type' => 'denda',
            'paid_at' => now()->toDateString(),
        ])
        ->assertSessionHasNoErrors();

    expect($transaction->payments()->sum('amount'))->toBe(600000);
});

it('does not accept payments from staff users', function () {
    $transaction = payableTransaction(500000);
    $staff = User::factory()->staff()->create();

    $this->actingAs($staff)
        ->post(route('payments.store'), [
            'transaction_id' => $transaction->id,
            'amount' => 100000,
            'method' => 'cash',
            'type' => 'dp',
            'paid_at' => now()->toDateString(),
        ])
        ->assertForbidden();

    expect($transaction->payments()->count())->toBe(0);
});

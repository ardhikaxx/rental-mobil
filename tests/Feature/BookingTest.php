<?php

use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;

function adminUser(): User
{
    return User::factory()->admin()->create();
}

function freeVehicle(array $attributes = []): Vehicle
{
    return Vehicle::factory()->create(array_merge(['daily_rate' => 300000, 'status' => VehicleStatus::Available], $attributes));
}

it('creates a transaction with computed totals and blocking status', function () {
    $vehicle = freeVehicle();
    $customer = Customer::factory()->create();

    $response = $this->actingAs(adminUser())->post(route('transactions.store'), [
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'booking_source' => 'walk_in',
        'start_at' => now()->addDay()->setTime(9, 0)->format('Y-m-d H:i:s'),
        'end_at' => now()->addDays(3)->setTime(9, 0)->format('Y-m-d H:i:s'),
        'discount' => 50000,
        'dp_amount' => 100000,
        'dp_method' => 'cash',
        'notes' => 'Uji coba.',
    ]);

    $transaction = Transaction::firstOrFail();

    $response->assertRedirect(route('transactions.show', $transaction));
    $response->assertSessionHasNoErrors();

    expect($transaction->status)->toBe(TransactionStatus::AwaitingPayment)
        ->and($transaction->rental_days)->toBe(2)
        ->and($transaction->subtotal)->toBe(600000)
        ->and($transaction->total)->toBe(550000)
        ->and($transaction->payments()->sum('amount'))->toBe(100000)
        ->and($transaction->transaction_number)->toStartWith('RNT-');
});

it('prevents double booking on an overlapping period', function () {
    $vehicle = freeVehicle();
    $customer = Customer::factory()->create();

    Transaction::factory()->booked()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'start_at' => now()->addDays(2)->setTime(9, 0),
        'end_at' => now()->addDays(5)->setTime(9, 0),
    ]);

    $response = $this->actingAs(adminUser())->post(route('transactions.store'), [
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'booking_source' => 'whatsapp',
        'start_at' => now()->addDays(4)->setTime(9, 0)->format('Y-m-d H:i:s'),
        'end_at' => now()->addDays(6)->setTime(9, 0)->format('Y-m-d H:i:s'),
        'dp_amount' => 0,
    ]);

    $response->assertSessionHasErrors('vehicle_id');
    expect(Transaction::count())->toBe(1);
});

it('allows renting the same vehicle when periods do not overlap', function () {
    $vehicle = freeVehicle();
    $customer = Customer::factory()->create();

    Transaction::factory()->booked()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'start_at' => now()->addDays(2)->setTime(9, 0),
        'end_at' => now()->addDays(4)->setTime(9, 0),
    ]);

    $response = $this->actingAs(adminUser())->post(route('transactions.store'), [
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'booking_source' => 'walk_in',
        'start_at' => now()->addDays(10)->setTime(9, 0)->format('Y-m-d H:i:s'),
        'end_at' => now()->addDays(12)->setTime(9, 0)->format('Y-m-d H:i:s'),
        'dp_amount' => 0,
    ]);

    $response->assertSessionHasNoErrors();
    expect(Transaction::count())->toBe(2);
});

it('blocks booking a vehicle that is under active maintenance', function () {
    $vehicle = freeVehicle();
    $customer = Customer::factory()->create();

    $vehicle->maintenances()->create([
        'type' => 'routine',
        'status' => 'in_progress',
        'start_date' => now()->toDateString(),
        'description' => 'Servis rutin.',
        'cost' => 0,
    ]);

    $response = $this->actingAs(adminUser())->post(route('transactions.store'), [
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'booking_source' => 'walk_in',
        'start_at' => now()->addDay()->setTime(9, 0)->format('Y-m-d H:i:s'),
        'end_at' => now()->addDays(2)->setTime(9, 0)->format('Y-m-d H:i:s'),
        'dp_amount' => 0,
    ]);

    $response->assertSessionHasErrors('vehicle_id');
    expect(Transaction::count())->toBe(0);
});

it('requires the configured minimum down payment before approving', function () {
    Setting::put('min_dp_percent', '20');

    $vehicle = freeVehicle();
    $customer = Customer::factory()->create();

    $transaction = Transaction::factory()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'status' => TransactionStatus::AwaitingPayment,
        'start_at' => now()->addDays(3)->setTime(9, 0),
        'end_at' => now()->addDays(5)->setTime(9, 0),
        'daily_rate' => 300000,
        'rental_days' => 2,
        'subtotal' => 600000,
        'total' => 600000,
    ]);

    $admin = adminUser();

    $this->actingAs($admin)
        ->post(route('transactions.approve', $transaction))
        ->assertSessionHasErrors('status');

    expect($transaction->fresh()->status)->toBe(TransactionStatus::AwaitingPayment);

    $transaction->payments()->create([
        'payment_number' => 'PAY-TEST-APPROVE',
        'amount' => 150000,
        'method' => 'cash',
        'type' => 'dp',
        'paid_at' => now()->toDateString(),
    ]);

    $this->actingAs($admin)
        ->post(route('transactions.approve', $transaction))
        ->assertSessionHasNoErrors();

    expect($transaction->fresh()->status)->toBe(TransactionStatus::Booked)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Booked);
});

it('cancels a transaction and releases the booked vehicle', function () {
    $vehicle = freeVehicle(['status' => VehicleStatus::Booked]);
    $customer = Customer::factory()->create();

    $transaction = Transaction::factory()->booked()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'start_at' => now()->addDays(2)->setTime(9, 0),
        'end_at' => now()->addDays(4)->setTime(9, 0),
    ]);

    $this->actingAs(adminUser())
        ->post(route('transactions.cancel', $transaction), ['reason' => 'Pelanggan batal.'])
        ->assertSessionHasNoErrors();

    expect($transaction->fresh()->status)->toBe(TransactionStatus::Cancelled)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Available)
        ->and($transaction->logs()->where('to_status', TransactionStatus::Cancelled->value)->count())->toBe(1);
});

it('does not allow cancelling a rented transaction', function () {
    $transaction = Transaction::factory()->rented()->create([
        'vehicle_id' => Vehicle::factory()->create()->id,
        'customer_id' => Customer::factory()->create()->id,
    ]);

    $this->actingAs(adminUser())
        ->post(route('transactions.cancel', $transaction))
        ->assertSessionHasErrors('status');

    expect($transaction->fresh()->status)->toBe(TransactionStatus::Rented);
});

it('does not allow editing a transaction that already progressed', function () {
    $transaction = Transaction::factory()->booked()->create([
        'vehicle_id' => Vehicle::factory()->create()->id,
        'customer_id' => Customer::factory()->create()->id,
    ]);

    $this->actingAs(adminUser())
        ->put(route('transactions.update', $transaction), [
            'booking_source' => 'walk_in',
            'start_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'end_at' => now()->addDays(4)->format('Y-m-d H:i:s'),
            'discount' => 0,
        ])
        ->assertSessionHasErrors('status');
});

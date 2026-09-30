<?php

use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\TireCondition;
use App\Enums\TransactionStatus;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;

beforeEach(function () {
    Setting::updateOrCreate(['key' => 'transaction_prefix'], ['value' => 'TRX']);
    Setting::updateOrCreate(['key' => 'payment_prefix'], ['value' => 'PAY']);
    Setting::updateOrCreate(['key' => 'min_dp_percent'], ['value' => '0']);
});

test('prevents creating transaction with a driver who is already booked for overlapping period', function () {
    $admin = User::factory()->admin()->create();
    $customer1 = Customer::factory()->create();
    $customer2 = Customer::factory()->create();
    $vehicle1 = Vehicle::factory()->create(['daily_rate' => 300000]);
    $vehicle2 = Vehicle::factory()->create(['daily_rate' => 400000]);
    $driver = Driver::factory()->create(['status' => 'available', 'is_active' => true, 'daily_rate' => 150000]);

    $start = now()->addDays(2)->setTime(10, 0);
    $end = now()->addDays(4)->setTime(10, 0);

    // First booking with driver succeeds
    $response1 = $this->actingAs($admin)->post(route('transactions.store'), [
        'customer_id' => $customer1->id,
        'vehicle_id' => $vehicle1->id,
        'booking_source' => 'whatsapp',
        'start_at' => $start->format('Y-m-d H:i'),
        'end_at' => $end->format('Y-m-d H:i'),
        'with_driver' => true,
        'driver_id' => $driver->id,
    ]);
    $response1->assertSessionHasNoErrors();

    // Second booking with the SAME driver overlapping the same period must fail
    $response2 = $this->actingAs($admin)->from(route('transactions.create'))->post(route('transactions.store'), [
        'customer_id' => $customer2->id,
        'vehicle_id' => $vehicle2->id,
        'booking_source' => 'whatsapp',
        'start_at' => $start->copy()->addDay()->format('Y-m-d H:i'),
        'end_at' => $end->copy()->addDay()->format('Y-m-d H:i'),
        'with_driver' => true,
        'driver_id' => $driver->id,
    ]);

    $response2->assertSessionHasErrors('driver_id');
});

test('driver status switches to busy on handover and back to available on return', function () {
    $admin = User::factory()->admin()->create();
    $customer = Customer::factory()->create();
    $vehicle = Vehicle::factory()->create(['daily_rate' => 300000, 'odometer' => 10000]);
    $driver = Driver::factory()->create(['status' => 'available', 'is_active' => true]);

    $transaction = Transaction::factory()->create([
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'with_driver' => true,
        'driver_id' => $driver->id,
        'status' => TransactionStatus::Booked,
        'start_at' => now()->startOfDay(),
        'end_at' => now()->addDays(2)->endOfDay(),
    ]);

    // Process handover
    $responseHandover = $this->actingAs($admin)->post(route('handover.store', $transaction), [
        'handover_at' => now()->format('Y-m-d H:i'),
        'odometer' => 10000,
        'fuel_level' => FuelLevel::Full->value,
        'exterior_condition' => ConditionLevel::Good->value,
        'interior_condition' => ConditionLevel::Good->value,
        'tire_condition' => TireCondition::Good->value,
        'completeness' => Completeness::Complete->value,
    ]);
    $responseHandover->assertSessionHasNoErrors();

    $driver->refresh();
    expect($driver->status)->toBe('busy');

    // Process return
    $responseReturn = $this->actingAs($admin)->post(route('return.store', $transaction), [
        'actual_return_at' => now()->addDays(2)->format('Y-m-d H:i'),
        'odometer' => 10250,
        'fuel_level' => FuelLevel::Full->value,
        'exterior_condition' => ConditionLevel::Good->value,
        'interior_condition' => ConditionLevel::Good->value,
        'tire_condition' => TireCondition::Good->value,
        'completeness' => Completeness::Complete->value,
        'next_status' => 'tersedia',
    ]);
    $responseReturn->assertSessionHasNoErrors();

    $driver->refresh();
    expect($driver->status)->toBe('available');
});

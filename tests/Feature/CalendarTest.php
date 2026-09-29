<?php

use App\Enums\TransactionStatus;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;

test('admin can access calendar page and fetch events as JSON array', function () {
    $admin = User::factory()->admin()->create();
    $vehicle = Vehicle::factory()->create();
    $customer = Customer::factory()->create();

    $trx = Transaction::factory()->booked()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'start_at' => now()->startOfDay()->addHours(9),
        'end_at' => now()->addDays(3)->startOfDay()->addHours(9),
    ]);

    // 1. Calendar page returns 200
    $this->actingAs($admin)->get(route('calendar.index'))->assertOk();

    // 2. Events endpoint returns JSON array
    $response = $this->actingAs($admin)->getJson(route('calendar.events', [
        'start' => now()->subDays(5)->toIso8601String(),
        'end' => now()->addDays(10)->toIso8601String(),
    ]));

    $response->assertOk();
    $events = $response->json();
    expect($events)->toBeArray();
    expect(count($events))->toBeGreaterThan(0);

    // 3. Find our created transaction event in the array
    $event = collect($events)->firstWhere('id', $trx->id);
    expect($event)->not->toBeNull();
    expect($event['title'])->toContain($trx->transaction_number);
    expect($event)->toHaveKeys(['id', 'title', 'start', 'end', 'color', 'url', 'extendedProps']);

    // 4. Test filtering by status
    $filteredResponse = $this->actingAs($admin)->getJson(route('calendar.events', [
        'status' => TransactionStatus::Booked->value,
    ]));
    $filteredResponse->assertOk();
    $filteredEvents = $filteredResponse->json();
    expect(collect($filteredEvents)->every(fn ($e) => $e['extendedProps']['status'] === TransactionStatus::Booked->value))->toBeTrue();

    // 5. Test filtering by vehicle
    $vehicleFilteredResponse = $this->actingAs($admin)->getJson(route('calendar.events', [
        'vehicle_id' => $vehicle->id,
    ]));
    $vehicleFilteredResponse->assertOk();
    $vehicleEvents = $vehicleFilteredResponse->json();
    expect(collect($vehicleEvents)->every(fn ($e) => $e['id'] === $trx->id))->toBeTrue();
});

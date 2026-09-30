<?php

use App\Enums\TransactionStatus;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;

test('vehicle report respects date filter and only aggregates data within range', function () {
    $superAdmin = User::factory()->superAdmin()->create();
    $customer = Customer::factory()->create();
    $vehicle = Vehicle::factory()->create();

    // Past month transaction (out of current month range)
    Transaction::factory()->create([
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'start_at' => now()->subMonths(2)->startOfMonth(),
        'end_at' => now()->subMonths(2)->startOfMonth()->addDays(3),
        'rental_days' => 3,
        'daily_rate' => 300000,
        'subtotal' => 900000,
        'total' => 900000,
        'status' => TransactionStatus::Completed,
    ]);

    // Current month transaction (in range)
    Transaction::factory()->create([
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'start_at' => now()->startOfMonth()->addDays(2),
        'end_at' => now()->startOfMonth()->addDays(4),
        'rental_days' => 2,
        'daily_rate' => 300000,
        'subtotal' => 600000,
        'total' => 600000,
        'status' => TransactionStatus::Completed,
    ]);

    $response = $this->actingAs($superAdmin)->get(route('reports.index', [
        'type' => 'vehicles',
        'preset' => 'custom',
        'from' => now()->startOfMonth()->toDateString(),
        'to' => now()->endOfMonth()->toDateString(),
    ]));

    $response->assertOk();
    $vehicleRows = $response->viewData('vehicleRows');
    $matched = $vehicleRows->firstWhere('vehicle.id', $vehicle->id);

    expect($matched)->not->toBeNull();
    // Only current month's transaction should be counted (2 days, 600,000 revenue)
    expect($matched['rentals'])->toBe(1);
    expect($matched['days'])->toBe(2);
    expect($matched['revenue'])->toBe(600000);
});

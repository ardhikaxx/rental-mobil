<?php

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;

test('authenticated user can download transaction invoice as PDF', function () {
    $admin = User::factory()->admin()->create();
    $customer = Customer::factory()->create();
    $vehicle = Vehicle::factory()->create();

    $transaction = Transaction::factory()->create([
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
    ]);

    $response = $this->actingAs($admin)->get(route('transactions.invoice.pdf', $transaction));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
    expect($response->headers->get('content-disposition'))->toContain("invoice-{$transaction->transaction_number}.pdf");
});

test('authenticated user can download transaction SPK agreement as PDF', function () {
    $admin = User::factory()->admin()->create();
    $customer = Customer::factory()->create();
    $vehicle = Vehicle::factory()->create();

    $transaction = Transaction::factory()->create([
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
    ]);

    $response = $this->actingAs($admin)->get(route('transactions.spk.pdf', $transaction));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
    expect($response->headers->get('content-disposition'))->toContain("spk-{$transaction->transaction_number}.pdf");
});

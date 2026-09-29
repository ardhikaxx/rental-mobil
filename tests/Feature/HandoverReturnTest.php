<?php

use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;

function makeRentedTransaction(array $attributes = []): Transaction
{
    return Transaction::factory()->rented()->create(array_merge([
        'vehicle_id' => Vehicle::factory()->create(['status' => VehicleStatus::Rented])->id,
        'customer_id' => Customer::factory()->create()->id,
    ], $attributes));
}

function staffUser(): User
{
    return User::factory()->staff()->create();
}

it('processes a handover and switches transaction and vehicle to rented', function () {
    $vehicle = Vehicle::factory()->create(['status' => VehicleStatus::Ready, 'odometer' => 20000]);
    $transaction = Transaction::factory()->booked()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => Customer::factory()->create()->id,
        'start_at' => now()->setTime(9, 0),
        'end_at' => now()->addDays(2)->setTime(9, 0),
    ]);

    $response = $this->actingAs(staffUser())->post(route('handover.store', $transaction), [
        'handover_at' => now()->format('Y-m-d H:i:s'),
        'odometer' => 20100,
        'fuel_level' => 'full',
        'exterior_condition' => 'good',
        'interior_condition' => 'good',
        'tire_condition' => 'good',
        'completeness' => 'lengkap',
        'existing_damage' => 'Baret kecil bumper belakang.',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('transactions.show', $transaction));

    expect($transaction->fresh()->status)->toBe(TransactionStatus::Rented)
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Rented)
        ->and($vehicle->fresh()->odometer)->toBe(20100)
        ->and($transaction->handoverInspection()->count())->toBe(1)
        ->and($transaction->logs()->where('action', 'handover')->count())->toBe(1);
});

it('refuses a handover on a cancelled transaction', function () {
    $vehicle = Vehicle::factory()->create(['odometer' => 50000]);

    $transaction = Transaction::factory()->cancelled()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => Customer::factory()->create()->id,
    ]);

    $this->actingAs(staffUser())
        ->post(route('handover.store', $transaction), [
            'handover_at' => now()->format('Y-m-d H:i:s'),
            'odometer' => 50100,
            'fuel_level' => 'full',
            'exterior_condition' => 'good',
            'interior_condition' => 'good',
            'tire_condition' => 'good',
            'completeness' => 'lengkap',
        ])
        ->assertSessionHasErrors('status');

    expect($transaction->fresh()->status)->toBe(TransactionStatus::Cancelled);
});

it('completes a return without late fee when returned on time', function () {
    Setting::put('late_fee_grace_minutes', '60');
    Setting::put('late_fee_mode', 'per_hour');
    Setting::put('late_fee_rate', '50000');

    $transaction = makeRentedTransaction([
        'start_at' => now()->subDays(2)->setTime(9, 0),
        'end_at' => now()->addDay()->setTime(9, 0),
    ]);
    $vehicle = $transaction->vehicle;

    $this->actingAs(staffUser())->post(route('return.store', $transaction), [
        'actual_return_at' => now()->format('Y-m-d H:i:s'),
        'odometer' => 21000,
        'fuel_level' => 'half',
        'exterior_condition' => 'good',
        'interior_condition' => 'good',
        'tire_condition' => 'good',
        'completeness' => 'lengkap',
        'next_status' => 'tersedia',
    ])->assertSessionHasNoErrors();

    $transaction->refresh();

    expect($transaction->status)->toBe(TransactionStatus::Completed)
        ->and($transaction->late_minutes)->toBe(0)
        ->and($transaction->late_fee)->toBe(0)
        ->and($transaction->actual_return_at)->not->toBeNull()
        ->and($vehicle->fresh()->status)->toBe(VehicleStatus::Available);
});

it('calculates the late fee from settings when the return is overdue', function () {
    Setting::put('late_fee_grace_minutes', '60');
    Setting::put('late_fee_mode', 'per_hour');
    Setting::put('late_fee_rate', '50000');

    $transaction = makeRentedTransaction([
        'start_at' => now()->subDays(5)->setTime(9, 0),
        'end_at' => now()->subHours(3),
    ]);

    $this->actingAs(staffUser())->post(route('return.store', $transaction), [
        'actual_return_at' => now()->format('Y-m-d H:i:s'),
        'odometer' => 22000,
        'fuel_level' => 'quarter',
        'exterior_condition' => 'good',
        'interior_condition' => 'good',
        'tire_condition' => 'good',
        'completeness' => 'lengkap',
        'next_status' => 'tersedia',
    ])->assertSessionHasNoErrors();

    $transaction->refresh();

    // 180 minutes late - 60 minutes grace = 120 minutes = 2 units × 50.000
    expect($transaction->late_minutes)->toBe(120)
        ->and($transaction->late_fee)->toBe(100000)
        ->and($transaction->status)->toBe(TransactionStatus::Completed);
});

it('directs a vehicle to maintenance when the return inspection finds major issues', function () {
    $transaction = makeRentedTransaction([
        'start_at' => now()->subDays(2)->setTime(9, 0),
        'end_at' => now()->subDay()->setTime(9, 0),
    ]);
    $vehicle = $transaction->vehicle;

    $this->actingAs(staffUser())->post(route('return.store', $transaction), [
        'actual_return_at' => now()->format('Y-m-d H:i:s'),
        'odometer' => 23000,
        'fuel_level' => 'empty',
        'exterior_condition' => 'major',
        'interior_condition' => 'good',
        'tire_condition' => 'replace',
        'completeness' => 'kurang',
        'missing_items' => 'Tool kit hilang.',
        'new_damage' => 'Spion kanan patah.',
        'next_status' => 'perawatan',
        'problem_description' => 'Spion patah dan ban kanan harus diganti.',
    ])->assertSessionHasNoErrors();

    $vehicle->refresh();

    expect($transaction->fresh()->status)->toBe(TransactionStatus::Completed)
        ->and($vehicle->status)->toBe(VehicleStatus::Maintenance)
        ->and($vehicle->maintenances()->count())->toBe(1)
        ->and($transaction->returnInspection->new_damage)->toContain('Spion');
});

it('requires a problem description when sending a vehicle to maintenance', function () {
    $transaction = makeRentedTransaction([
        'start_at' => now()->subDays(2)->setTime(9, 0),
        'end_at' => now()->subDay()->setTime(9, 0),
    ]);

    $this->actingAs(staffUser())->post(route('return.store', $transaction), [
        'actual_return_at' => now()->format('Y-m-d H:i:s'),
        'odometer' => 23000,
        'fuel_level' => 'empty',
        'exterior_condition' => 'major',
        'interior_condition' => 'good',
        'tire_condition' => 'good',
        'completeness' => 'lengkap',
        'next_status' => 'perawatan',
    ])->assertSessionHasErrors('problem_description');

    expect($transaction->fresh()->status)->toBe(TransactionStatus::Rented);
});

it('does not let staff manually flip a rented vehicle back to available', function () {
    $vehicle = Vehicle::factory()->create(['status' => VehicleStatus::Rented]);

    $this->actingAs(staffUser())
        ->post(route('vehicles.status', $vehicle), ['status' => 'tersedia'])
        ->assertForbidden();

    expect($vehicle->fresh()->status)->toBe(VehicleStatus::Rented);
});

it('lets staff run the cleaning readiness workflow', function () {
    $vehicle = Vehicle::factory()->create(['status' => VehicleStatus::Available]);
    $staff = staffUser();

    $this->actingAs($staff)->post(route('vehicles.mark-cleaning', $vehicle))->assertSessionHasNoErrors();
    expect($vehicle->fresh()->status)->toBe(VehicleStatus::Cleaning);

    $this->actingAs($staff)->post(route('vehicles.mark-ready', $vehicle))->assertSessionHasNoErrors();
    expect($vehicle->fresh()->status)->toBe(VehicleStatus::Ready);
});

it('blocks the cleaning workflow for invalid transitions', function () {
    $vehicle = Vehicle::factory()->create(['status' => VehicleStatus::Available]);

    $this->actingAs(staffUser())
        ->post(route('vehicles.mark-ready', $vehicle))
        ->assertSessionHasErrors('status');

    expect($vehicle->fresh()->status)->toBe(VehicleStatus::Available);
});

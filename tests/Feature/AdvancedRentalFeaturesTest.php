<?php

use App\Enums\VehicleStatus;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Setting::updateOrCreate(['key' => 'transaction_prefix'], ['value' => 'RNT']);
    Setting::updateOrCreate(['key' => 'payment_prefix'], ['value' => 'PAY']);
});

test('admin can upload customer with KTP and SIM, verify, and reject documents', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();

    $fileKtp = UploadedFile::fake()->image('ktp.jpg', 600, 400);
    $fileSim = UploadedFile::fake()->image('sim.jpg', 600, 400);

    // Create customer with files
    $response = $this->actingAs($admin)->post(route('customers.store'), [
        'name' => 'Bambang Trihatmojo',
        'id_number' => '3509211010880009',
        'sim_number' => '1004-8810-000999',
        'phone' => '081298765432',
        'email' => 'bambang.tri@test.local',
        'ktp_photo' => $fileKtp,
        'sim_photo' => $fileSim,
    ]);

    $response->assertSessionHasNoErrors();
    $customer = Customer::where('id_number', '3509211010880009')->firstOrFail();
    expect($customer->sim_number)->toBe('1004-8810-000999');
    expect($customer->isVerified())->toBeTrue();
    expect(File::exists(storage_path('uploads/customers/ktp/'.$customer->ktp_photo)))->toBeTrue();
    expect(File::exists(storage_path('uploads/customers/sim/'.$customer->sim_photo)))->toBeTrue();

    // Clean up created files
    File::delete(storage_path('uploads/customers/ktp/'.$customer->ktp_photo));
    File::delete(storage_path('uploads/customers/sim/'.$customer->sim_photo));

    // Reject customer document
    $this->actingAs($admin)->post(route('customers.reject', $customer), [
        'rejection_reason' => 'Foto SIM buram tidak terbaca.',
    ])->assertSessionHasNoErrors();

    $customer->refresh();
    expect($customer->isRejected())->toBeTrue();
    expect($customer->rejection_reason)->toBe('Foto SIM buram tidak terbaca.');

    // Re-verify customer document
    $this->actingAs($admin)->post(route('customers.verify', $customer))
        ->assertSessionHasNoErrors();

    $customer->refresh();
    expect($customer->isVerified())->toBeTrue();
    expect($customer->rejection_reason)->toBeNull();
});

test('admin can manage drivers and view them', function () {
    $admin = User::factory()->admin()->create();

    $driver = Driver::create([
        'code' => 'DRV-999',
        'name' => 'Supir Uji Coba',
        'phone' => '081233445566',
        'sim_type' => 'SIM A',
        'sim_number' => '999988887777',
        'daily_rate' => 160000,
        'status' => 'available',
        'is_active' => true,
    ]);

    $this->actingAs($admin)->get(route('drivers.index'))
        ->assertOk()
        ->assertSee('Supir Uji Coba')
        ->assertSee('DRV-999');

    // Update driver
    $this->actingAs($admin)->put(route('drivers.update', $driver), [
        'code' => 'DRV-999',
        'name' => 'Supir Uji Coba Update',
        'phone' => '081233445566',
        'sim_type' => 'SIM B1',
        'sim_number' => '999988887777',
        'daily_rate' => 175000,
        'status' => 'busy',
        'is_active' => true,
    ])->assertSessionHasNoErrors();

    $driver->refresh();
    expect($driver->name)->toBe('Supir Uji Coba Update');
    expect($driver->daily_rate)->toBe(175000);
    expect($driver->status)->toBe('busy');
});

test('transaction calculation accurately includes optional driver fee and security deposit', function () {
    $admin = User::factory()->admin()->create();
    $vehicle = Vehicle::factory()->create(['daily_rate' => 300000, 'status' => VehicleStatus::Available]);
    $customer = Customer::factory()->create();
    $driver = Driver::create([
        'code' => 'DRV-777',
        'name' => 'Supir Transaksi Test',
        'phone' => '081211112222',
        'sim_type' => 'SIM A',
        'daily_rate' => 150000,
        'status' => 'available',
        'is_active' => true,
    ]);

    $startAt = Carbon::now()->addDays(50)->setTime(9, 0)->format('Y-m-d H:i');
    $endAt = Carbon::now()->addDays(52)->setTime(9, 0)->format('Y-m-d H:i'); // 2 days

    $response = $this->actingAs($admin)->post(route('transactions.store'), [
        'customer_id' => $customer->id,
        'vehicle_id' => $vehicle->id,
        'booking_source' => 'whatsapp',
        'start_at' => $startAt,
        'end_at' => $endAt,
        'with_driver' => '1',
        'driver_id' => $driver->id,
        'deposit_type' => 'tunai',
        'deposit_amount' => 500000,
        'deposit_notes' => 'Uang jaminan tunai disimpan kasir',
        'discount' => 50000,
        'dp_amount' => 300000,
        'dp_method' => 'cash',
        'save_as_draft' => '0',
    ]);

    $response->assertSessionHasNoErrors();
    $transaction = Transaction::latest('id')->firstOrFail();
    expect($transaction->with_driver)->toBeTrue();
    expect($transaction->driver_id)->toBe($driver->id);
    expect($transaction->driver_rate)->toBe(150000);
    expect($transaction->driver_fee)->toBe(300000); // 2 days * 150.000
    expect($transaction->deposit_type)->toBe('tunai');
    expect($transaction->deposit_amount)->toBe(500000);
    expect($transaction->deposit_status)->toBe('pending');

    // Expected subtotal = (daily_rate * 2) + 300000
    $expectedSubtotal = ($vehicle->daily_rate * 2) + 300000;
    expect($transaction->subtotal)->toBe($expectedSubtotal);
    expect($transaction->total)->toBe($expectedSubtotal - 50000);

    // Update deposit status
    $this->actingAs($admin)->post(route('transactions.deposit.update', $transaction), [
        'deposit_status' => 'held',
        'deposit_notes' => 'Jaminan telah diterima fisik di kasir',
    ])->assertSessionHasNoErrors();

    $transaction->refresh();
    expect($transaction->deposit_status)->toBe('held');
});

test('surat perjanjian sewa (SPK) can be rendered for print', function () {
    $admin = User::factory()->admin()->create();
    $vehicle = Vehicle::factory()->create(['daily_rate' => 300000, 'status' => VehicleStatus::Available]);
    $customer = Customer::factory()->create();

    $transaction = Transaction::factory()->booked()->create([
        'vehicle_id' => $vehicle->id,
        'customer_id' => $customer->id,
        'daily_rate' => 300000,
        'rental_days' => 2,
        'subtotal' => 600000,
        'total' => 600000,
    ]);

    $response = $this->actingAs($admin)->get(route('transactions.spk', $transaction));
    $response->assertOk()
        ->assertSee('SURAT PERJANJIAN SEWA KENDARAAN')
        ->assertSee($transaction->transaction_number)
        ->assertSee('PASAL 1')
        ->assertSee('PASAL 3')
        ->assertSee('Penyewa Kendaraan');
});

test('super admin can export financial and vehicle reports to excel csv', function () {
    $superAdmin = User::factory()->superAdmin()->create();

    // Export income
    $responseIncome = $this->actingAs($superAdmin)->get(route('reports.export', ['type' => 'income']));
    $responseIncome->assertOk();
    expect($responseIncome->headers->get('content-type'))->toContain('text/csv');
    expect($responseIncome->headers->get('content-disposition'))->toContain('attachment; filename=laporan-income-');

    // Export vehicles
    $responseVehicles = $this->actingAs($superAdmin)->get(route('reports.export', ['type' => 'vehicles']));
    $responseVehicles->assertOk();
    expect($responseVehicles->headers->get('content-disposition'))->toContain('attachment; filename=laporan-vehicles-');

    // Export customers
    $responseCustomers = $this->actingAs($superAdmin)->get(route('reports.export', ['type' => 'customers']));
    $responseCustomers->assertOk();
    expect($responseCustomers->headers->get('content-disposition'))->toContain('attachment; filename=laporan-customers-');
});

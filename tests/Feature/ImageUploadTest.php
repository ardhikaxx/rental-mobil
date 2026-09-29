<?php

use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\InspectionType;
use App\Enums\TireCondition;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Driver;
use App\Models\Inspection;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\PhotoStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

test('admin can upload vehicle photo on create, view via streaming route, replace, delete photo on edit, and photo is removed on vehicle delete', function () {
    $admin = User::factory()->superAdmin()->create();

    $photo1 = UploadedFile::fake()->image('avanza.jpg', 640, 480);

    // 1. Create vehicle with photo
    $response = $this->actingAs($admin)->post(route('vehicles.store'), [
        'code' => 'AVZ-TEST-01',
        'brand' => 'Toyota',
        'model' => 'Avanza G',
        'type' => VehicleType::MPV->value,
        'year' => 2023,
        'color' => 'Hitam Metalik',
        'license_plate' => 'B 1234 TST',
        'daily_rate' => 350000,
        'fuel_level' => FuelLevel::Full->value,
        'photo' => $photo1,
    ]);

    $response->assertSessionHasNoErrors();
    $vehicle = Vehicle::where('code', 'AVZ-TEST-01')->firstOrFail();
    expect($vehicle->photo)->not->toBeNull();

    $photoPath1 = storage_path('uploads/vehicles/'.$vehicle->photo);
    expect(File::exists($photoPath1))->toBeTrue();

    // 2. Stream photo via /uploads/vehicles/{filename}
    $streamResponse = $this->get('/uploads/vehicles/'.$vehicle->photo);
    $streamResponse->assertStatus(200);
    $streamResponse->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');

    // 3. Update vehicle with new photo (replaces old photo)
    $photo2 = UploadedFile::fake()->image('avanza_new.jpg', 640, 480);
    $updateResponse = $this->actingAs($admin)->put(route('vehicles.update', $vehicle), [
        'code' => 'AVZ-TEST-01',
        'brand' => 'Toyota',
        'model' => 'Avanza G Facelift',
        'type' => VehicleType::MPV->value,
        'year' => 2023,
        'color' => 'Putih Mutiara',
        'license_plate' => 'B 1234 TST',
        'daily_rate' => 375000,
        'fuel_level' => FuelLevel::Full->value,
        'photo' => $photo2,
    ]);

    $updateResponse->assertSessionHasNoErrors();
    $vehicle->refresh();
    $photoPath2 = storage_path('uploads/vehicles/'.$vehicle->photo);
    expect(File::exists($photoPath1))->toBeFalse(); // Old photo removed
    expect(File::exists($photoPath2))->toBeTrue();  // New photo exists

    // 4. Delete photo via checkbox
    $deletePhotoResponse = $this->actingAs($admin)->put(route('vehicles.update', $vehicle), [
        'code' => 'AVZ-TEST-01',
        'brand' => 'Toyota',
        'model' => 'Avanza G Facelift',
        'type' => VehicleType::MPV->value,
        'year' => 2023,
        'color' => 'Putih Mutiara',
        'license_plate' => 'B 1234 TST',
        'daily_rate' => 375000,
        'fuel_level' => FuelLevel::Full->value,
        'delete_photo' => 1,
    ]);

    $deletePhotoResponse->assertSessionHasNoErrors();
    $vehicle->refresh();
    expect($vehicle->photo)->toBeNull();
    expect(File::exists($photoPath2))->toBeFalse();

    // 5. Add photo again and test vehicle deletion cleans up photo file
    $photo3 = UploadedFile::fake()->image('avanza_final.jpg', 640, 480);
    $this->actingAs($admin)->put(route('vehicles.update', $vehicle), [
        'code' => 'AVZ-TEST-01',
        'brand' => 'Toyota',
        'model' => 'Avanza G Facelift',
        'type' => VehicleType::MPV->value,
        'year' => 2023,
        'color' => 'Putih Mutiara',
        'license_plate' => 'B 1234 TST',
        'daily_rate' => 375000,
        'fuel_level' => FuelLevel::Full->value,
        'photo' => $photo3,
    ]);

    $vehicle->refresh();
    $photoPath3 = storage_path('uploads/vehicles/'.$vehicle->photo);
    expect(File::exists($photoPath3))->toBeTrue();

    $this->actingAs($admin)->delete(route('vehicles.destroy', $vehicle))->assertRedirect(route('vehicles.index'));
    expect(File::exists($photoPath3))->toBeFalse();
});

test('admin can upload driver photo, view, replace, and delete', function () {
    $admin = User::factory()->admin()->create();

    $photo1 = UploadedFile::fake()->image('driver.jpg', 400, 400);

    // 1. Create driver with photo
    $response = $this->actingAs($admin)->post(route('drivers.store'), [
        'code' => 'DRV-TEST-01',
        'name' => 'Sopir Teladan',
        'phone' => '081234567899',
        'sim_type' => 'SIM A',
        'sim_number' => 'SIM-999988',
        'daily_rate' => 150000,
        'status' => 'available',
        'photo' => $photo1,
    ]);

    $response->assertSessionHasNoErrors();
    $driver = Driver::where('code', 'DRV-TEST-01')->firstOrFail();
    expect($driver->photo)->not->toBeNull();

    $photoPath1 = storage_path('uploads/drivers/'.$driver->photo);
    expect(File::exists($photoPath1))->toBeTrue();

    // 2. Stream driver photo
    $stream = $this->get('/uploads/drivers/'.$driver->photo);
    $stream->assertStatus(200);

    // 3. Delete driver photo via edit form
    $this->actingAs($admin)->put(route('drivers.update', $driver), [
        'code' => 'DRV-TEST-01',
        'name' => 'Sopir Teladan',
        'phone' => '081234567899',
        'sim_type' => 'SIM A',
        'sim_number' => 'SIM-999988',
        'daily_rate' => 150000,
        'status' => 'available',
        'delete_photo' => 1,
    ])->assertSessionHasNoErrors();

    $driver->refresh();
    expect($driver->photo)->toBeNull();
    expect(File::exists($photoPath1))->toBeFalse();

    // 4. Clean up driver
    $driver->delete();
});

test('inspection photos are stored in storage/uploads and served properly without storage:link', function () {
    $staff = User::factory()->staff()->create();
    $vehicle = Vehicle::factory()->create(['status' => VehicleStatus::Available]);

    $inspection = Inspection::create([
        'vehicle_id' => $vehicle->id,
        'type' => InspectionType::Routine,
        'inspected_at' => now(),
        'inspected_by' => $staff->id,
        'odometer' => 15000,
        'fuel_level' => FuelLevel::Full,
        'exterior_condition' => ConditionLevel::Good,
        'interior_condition' => ConditionLevel::Good,
        'tire_condition' => TireCondition::Good,
    ]);

    $photoFile = UploadedFile::fake()->image('bumper.jpg', 640, 480);
    app(PhotoStorage::class)->store($inspection, [$photoFile]);

    $inspectionPhoto = $inspection->photos()->firstOrFail();
    $filePath = storage_path('uploads/inspections/'.$inspectionPhoto->path);
    expect(File::exists($filePath))->toBeTrue();

    // Stream via media route
    $this->actingAs($staff)
        ->get(route('media.inspection-photo', $inspectionPhoto))
        ->assertStatus(200);

    // Stream via general uploads route
    $this->get('/uploads/inspections/'.$inspectionPhoto->path)
        ->assertStatus(200);

    // Clean up
    File::delete($filePath);
    $inspectionPhoto->delete();
    $inspection->delete();
    $vehicle->delete();
});

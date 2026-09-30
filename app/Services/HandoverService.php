<?php

namespace App\Services;

use App\Enums\FuelLevel;
use App\Enums\InspectionType;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Driver;
use App\Models\Inspection;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HandoverService
{
    public function __construct(
        private VehicleAvailabilityService $availability,
        private DriverAvailabilityService $driverAvailability,
        private PhotoStorage $photos,
    ) {}

    /**
     * Process a vehicle handover: record the initial condition inspection,
     * switch the transaction to "rented" and the vehicle to "sedang disewa"
     * atomically.
     *
     * @param  array<string, mixed>  $data
     */
    public function process(Transaction $transaction, array $data, User $user): Transaction
    {
        return DB::transaction(function () use ($transaction, $data, $user) {
            /** @var Transaction $transaction */
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if (! $transaction->canBeHandedOver()) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya transaksi berstatus Disetujui atau Siap Diserahkan yang dapat diserahkan. Status saat ini: '.$transaction->status->label().'.',
                ]);
            }

            $vehicle = Vehicle::lockForUpdate()->findOrFail($transaction->vehicle_id);

            if ($vehicle->status->value === 'disewa') {
                throw ValidationException::withMessages([
                    'status' => 'Kendaraan sedang disewa oleh transaksi lain.',
                ]);
            }

            $this->availability->assertAvailable($vehicle, $transaction->start_at, $transaction->end_at, $transaction->id);

            if ($transaction->with_driver && $transaction->driver_id) {
                $driver = Driver::lockForUpdate()->findOrFail($transaction->driver_id);
                $this->driverAvailability->assertAvailable($driver, $transaction->start_at, $transaction->end_at, $transaction->id);
            }

            $inspection = Inspection::create([
                'vehicle_id' => $vehicle->id,
                'transaction_id' => $transaction->id,
                'type' => InspectionType::Handover,
                'inspected_at' => $data['handover_at'],
                'inspected_by' => $user->id,
                'odometer' => $data['odometer'],
                'fuel_level' => $data['fuel_level'],
                'exterior_condition' => $data['exterior_condition'],
                'interior_condition' => $data['interior_condition'],
                'tire_condition' => $data['tire_condition'],
                'completeness' => $data['completeness'],
                'missing_items' => $data['missing_items'] ?? null,
                'existing_damage' => $data['existing_damage'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $this->photos->store($inspection, $data['photos'] ?? []);

            $vehicle->update([
                'odometer' => max((int) $vehicle->odometer, (int) $data['odometer']),
                'fuel_level' => $data['fuel_level'],
                'status' => VehicleStatus::Rented,
            ]);

            if ($transaction->with_driver && $transaction->driver_id) {
                $driver = Driver::lockForUpdate()->find($transaction->driver_id);
                if ($driver && $driver->status !== 'inactive') {
                    $driver->update(['status' => 'busy']);
                }
            }

            $fromStatus = $transaction->status->value;
            $transaction->update([
                'status' => TransactionStatus::Rented,
                'handover_at' => $data['handover_at'],
                'handed_over_by' => $user->id,
            ]);

            $transaction->logs()->create([
                'user_id' => $user->id,
                'action' => 'handover',
                'from_status' => $fromStatus,
                'to_status' => TransactionStatus::Rented->value,
                'description' => sprintf(
                    'Kendaraan diserahkan kepada pelanggan oleh %s (odometer %s km, bahan bakar %s).',
                    $user->name,
                    number_format((int) $data['odometer']),
                    FuelLevel::from($data['fuel_level'])->label(),
                ),
            ]);

            return $transaction->refresh();
        });
    }
}

<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VehicleStatusService
{
    /**
     * Manually change a vehicle status (super admin override). Business-owned
     * statuses (dibooking, sedang disewa) are protected and can only be changed
     * through the transaction workflow.
     */
    public function changeStatus(Vehicle $vehicle, VehicleStatus $target, User $user): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $target) {
            /** @var Vehicle $vehicle */
            $vehicle = Vehicle::lockForUpdate()->findOrFail($vehicle->id);
            $current = $vehicle->status;

            if ($current === $target) {
                return $vehicle;
            }

            if ($current === VehicleStatus::Rented) {
                throw ValidationException::withMessages([
                    'status' => 'Kendaraan sedang disewa. Ubah status hanya dapat dilakukan melalui proses pengembalian kendaraan.',
                ]);
            }

            if ($target === VehicleStatus::Rented || $target === VehicleStatus::Booked) {
                throw ValidationException::withMessages([
                    'status' => 'Status "'.$target->label().'" hanya dihasilkan dari proses transaksi (persetujuan booking / serah terima), bukan dari ubah status manual.',
                ]);
            }

            $underMaintenance = $vehicle->maintenances()
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->exists();

            if ($underMaintenance && $target !== VehicleStatus::Maintenance) {
                throw ValidationException::withMessages([
                    'status' => 'Kendaraan memiliki perawatan aktif. Selesaikan atau batalkan perawatan terlebih dahulu.',
                ]);
            }

            if ($target === VehicleStatus::Available) {
                $hasActiveBooking = $vehicle->transactions()
                    ->whereIn('status', TransactionStatus::blocking())
                    ->exists();

                if ($hasActiveBooking) {
                    throw ValidationException::withMessages([
                        'status' => 'Kendaraan masih terikat booking/rental aktif dan tidak dapat ditandai tersedia.',
                    ]);
                }
            }

            $vehicle->update(['status' => $target]);

            return $vehicle;
        });
    }

    /**
     * Staff action: mark a vehicle as being cleaned.
     */
    public function markCleaning(Vehicle $vehicle, User $user): Vehicle
    {
        return $this->quickTransition($vehicle, $user, [VehicleStatus::Available, VehicleStatus::Ready], VehicleStatus::Cleaning);
    }

    /**
     * Staff action: mark a cleaned vehicle as ready to go.
     */
    public function markReady(Vehicle $vehicle, User $user): Vehicle
    {
        return $this->quickTransition($vehicle, $user, [VehicleStatus::Cleaning], VehicleStatus::Ready);
    }

    /**
     * Staff action: mark a ready vehicle as available for rent again.
     */
    public function markAvailable(Vehicle $vehicle, User $user): Vehicle
    {
        $vehicle = $this->quickTransition($vehicle, $user, [VehicleStatus::Ready], VehicleStatus::Available);

        return $vehicle;
    }

    /**
     * Recompute the vehicle status after a maintenance record is completed.
     */
    public function afterMaintenanceCompleted(Vehicle $vehicle): void
    {
        $hasActiveBooking = $vehicle->transactions()
            ->whereIn('status', [TransactionStatus::Booked->value, TransactionStatus::ReadyForHandover->value])
            ->exists();

        $vehicle->update([
            'status' => $hasActiveBooking ? VehicleStatus::Booked : VehicleStatus::Available,
        ]);
    }

    /**
     * @param  array<int, VehicleStatus>  $allowedFrom
     */
    private function quickTransition(Vehicle $vehicle, User $user, array $allowedFrom, VehicleStatus $target): Vehicle
    {
        return DB::transaction(function () use ($vehicle, $allowedFrom, $target) {
            /** @var Vehicle $vehicle */
            $vehicle = Vehicle::lockForUpdate()->findOrFail($vehicle->id);

            if (! in_array($vehicle->status, $allowedFrom, true)) {
                $from = implode(', ', array_map(fn (VehicleStatus $s) => $s->label(), $allowedFrom));

                throw ValidationException::withMessages([
                    'status' => "Aksi ini hanya dapat dilakukan dari status {$from}. Status saat ini: {$vehicle->status->label()}.",
                ]);
            }

            if ($target === VehicleStatus::Available) {
                $hasActiveBooking = $vehicle->transactions()
                    ->whereIn('status', TransactionStatus::blocking())
                    ->exists();

                if ($hasActiveBooking) {
                    throw ValidationException::withMessages([
                        'status' => 'Kendaraan masih terikat booking/rental aktif dan tidak dapat ditandai tersedia.',
                    ]);
                }
            }

            $vehicle->update(['status' => $target]);

            return $vehicle;
        });
    }
}

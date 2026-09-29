<?php

namespace App\Services;

use App\Enums\MaintenanceStatus;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Transaction;
use App\Models\Vehicle;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class VehicleAvailabilityService
{
    /**
     * Find blocking transactions whose period overlaps the given range.
     *
     * @return Collection<int, Transaction>
     */
    public function conflicts(
        int $vehicleId,
        CarbonInterface $start,
        CarbonInterface $end,
        ?int $excludeTransactionId = null,
        bool $lock = false,
    ): Collection {
        $query = Transaction::query()
            ->where('vehicle_id', $vehicleId)
            ->whereIn('status', TransactionStatus::blocking())
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start);

        if ($excludeTransactionId !== null) {
            $query->where('id', '!=', $excludeTransactionId);
        }

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->get();
    }

    public function isAvailable(int $vehicleId, CarbonInterface $start, CarbonInterface $end, ?int $excludeTransactionId = null): bool
    {
        return $this->conflicts($vehicleId, $start, $end, $excludeTransactionId)->isEmpty();
    }

    /**
     * Vehicle ids that cannot be rented for the given period because another
     * blocking transaction overlaps it.
     *
     * @return array<int, int>
     */
    public function blockedVehicleIds(CarbonInterface $start, CarbonInterface $end): array
    {
        return Transaction::query()
            ->whereIn('status', TransactionStatus::blocking())
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->pluck('vehicle_id')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Throw a validation error when the vehicle cannot be rented for the period.
     * Should be called inside a database transaction that locks the vehicle row.
     *
     * @throws ValidationException
     */
    public function assertAvailable(Vehicle $vehicle, CarbonInterface $start, CarbonInterface $end, ?int $excludeTransactionId = null): void
    {
        if (! $vehicle->is_active) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan '.$vehicle->code.' tidak aktif dan tidak dapat disewakan.',
            ]);
        }

        if ($vehicle->status === VehicleStatus::Unavailable) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan '.$vehicle->code.' berstatus tidak tersedia.',
            ]);
        }

        $underMaintenance = $vehicle->maintenances()
            ->whereIn('status', MaintenanceStatus::active())
            ->exists();

        if ($underMaintenance) {
            throw ValidationException::withMessages([
                'vehicle_id' => 'Kendaraan '.$vehicle->code.' sedang dalam perawatan dan tidak dapat disewakan.',
            ]);
        }

        $conflict = $this->conflicts((int) $vehicle->id, $start, $end, $excludeTransactionId, lock: true)->first();

        if ($conflict !== null) {
            throw ValidationException::withMessages([
                'vehicle_id' => sprintf(
                    'Kendaraan bentrok dengan transaksi %s (%s s/d %s). Pilih kendaraan lain atau ubah periode rental.',
                    $conflict->transaction_number,
                    tanggal_waktu($conflict->start_at),
                    tanggal_waktu($conflict->end_at),
                ),
            ]);
        }
    }
}

<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Models\Driver;
use App\Models\Transaction;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class DriverAvailabilityService
{
    /**
     * Find blocking transactions where this driver is assigned whose period overlaps the given range.
     *
     * @return Collection<int, Transaction>
     */
    public function conflicts(
        int $driverId,
        CarbonInterface $start,
        CarbonInterface $end,
        ?int $excludeTransactionId = null,
        bool $lock = false,
    ): Collection {
        $query = Transaction::query()
            ->where('driver_id', $driverId)
            ->where('with_driver', true)
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

    public function isAvailable(
        int $driverId,
        CarbonInterface $start,
        CarbonInterface $end,
        ?int $excludeTransactionId = null,
    ): bool {
        return $this->conflicts($driverId, $start, $end, $excludeTransactionId)->isEmpty();
    }

    /**
     * Driver ids that cannot be assigned for the given period because another
     * blocking transaction overlaps it.
     *
     * @return array<int, int>
     */
    public function blockedDriverIds(CarbonInterface $start, CarbonInterface $end, ?int $excludeTransactionId = null): array
    {
        $query = Transaction::query()
            ->where('with_driver', true)
            ->whereNotNull('driver_id')
            ->whereIn('status', TransactionStatus::blocking())
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start);

        if ($excludeTransactionId !== null) {
            $query->where('id', '!=', $excludeTransactionId);
        }

        return $query->pluck('driver_id')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Assert that the driver is available for the given rental period.
     *
     * @throws ValidationException
     */
    public function assertAvailable(
        Driver $driver,
        CarbonInterface $start,
        CarbonInterface $end,
        ?int $excludeTransactionId = null,
    ): void {
        if (! $driver->is_active) {
            throw ValidationException::withMessages([
                'driver_id' => "Supir {$driver->name} ({$driver->code}) tidak aktif dan tidak dapat ditugaskan.",
            ]);
        }

        if (in_array($driver->status, ['inactive', 'off'], true)) {
            throw ValidationException::withMessages([
                'driver_id' => "Supir {$driver->name} ({$driver->code}) sedang libur / tidak bertugas.",
            ]);
        }

        $conflict = $this->conflicts((int) $driver->id, $start, $end, $excludeTransactionId, lock: true)->first();

        if ($conflict !== null) {
            throw ValidationException::withMessages([
                'driver_id' => sprintf(
                    'Supir %s (%s) bentrok dengan transaksi %s (%s s/d %s). Pilih supir lain atau ubah periode rental.',
                    $driver->name,
                    $driver->code,
                    $conflict->transaction_number,
                    tanggal_waktu($conflict->start_at),
                    tanggal_waktu($conflict->end_at),
                ),
            ]);
        }
    }
}

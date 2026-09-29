<?php

namespace App\Services;

use App\Enums\ConditionLevel;
use App\Enums\InspectionType;
use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Models\Inspection;
use App\Models\Maintenance;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturnService
{
    public function __construct(
        private LateFeeCalculator $lateFee,
        private PhotoStorage $photos,
    ) {}

    /**
     * Process a vehicle return: record the final condition inspection, compute
     * the late penalty from business settings, close the transaction, and move
     * the vehicle to the status dictated by the inspection result.
     *
     * @param  array<string, mixed>  $data
     */
    public function process(Transaction $transaction, array $data, User $user): Transaction
    {
        return DB::transaction(function () use ($transaction, $data, $user) {
            /** @var Transaction $transaction */
            $transaction = Transaction::lockForUpdate()->findOrFail($transaction->id);

            if (! $transaction->canBeReturned()) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya transaksi berstatus Sedang Disewa yang dapat diproses pengembaliannya. Status saat ini: '.$transaction->status->label().'.',
                ]);
            }

            $vehicle = Vehicle::lockForUpdate()->findOrFail($transaction->vehicle_id);
            $actualReturnAt = Carbon::parse($data['actual_return_at']);

            $penalty = $this->lateFee->calculate($transaction->end_at, $actualReturnAt);

            $nextStatus = VehicleStatus::from($data['next_status']);

            $inspection = Inspection::create([
                'vehicle_id' => $vehicle->id,
                'transaction_id' => $transaction->id,
                'type' => InspectionType::Return,
                'inspected_at' => $actualReturnAt,
                'inspected_by' => $user->id,
                'odometer' => $data['odometer'],
                'fuel_level' => $data['fuel_level'],
                'exterior_condition' => $data['exterior_condition'],
                'interior_condition' => $data['interior_condition'],
                'tire_condition' => $data['tire_condition'],
                'completeness' => $data['completeness'],
                'missing_items' => $data['missing_items'] ?? null,
                'existing_damage' => $transaction->handoverInspection?->existing_damage,
                'new_damage' => $data['new_damage'] ?? null,
                'notes' => $data['notes'] ?? null,
                'vehicle_status_after' => $nextStatus->value,
            ]);

            $this->photos->store($inspection, $data['photos'] ?? []);

            $fromStatus = $transaction->status->value;

            $transaction->update([
                'actual_return_at' => $actualReturnAt,
                'returned_by' => $user->id,
                'late_minutes' => $penalty['minutes'],
                'late_fee' => $penalty['fee'],
                'status' => TransactionStatus::Completed,
            ]);

            $vehicle->update([
                'odometer' => max((int) $vehicle->odometer, (int) $data['odometer']),
                'fuel_level' => $data['fuel_level'],
                'status' => $nextStatus,
            ]);

            if ($nextStatus === VehicleStatus::Maintenance) {
                Maintenance::create([
                    'vehicle_id' => $vehicle->id,
                    'type' => MaintenanceType::Other,
                    'status' => MaintenanceStatus::InProgress,
                    'start_date' => now()->toDateString(),
                    'odometer' => (int) $data['odometer'],
                    'description' => $data['problem_description'],
                    'cost' => 0,
                    'recorded_by' => $user->id,
                    'notes' => "Dibuat otomatis dari hasil pengembalian transaksi {$transaction->transaction_number}.",
                ]);
            }

            $transaction->logs()->create([
                'user_id' => $user->id,
                'action' => 'return',
                'from_status' => $fromStatus,
                'to_status' => TransactionStatus::Completed->value,
                'description' => sprintf(
                    'Kendaraan dikembalikan dan diperiksa oleh %s (odometer %s km, kondisi akhir: %s).',
                    $user->name,
                    number_format((int) $data['odometer']),
                    $nextStatus->label(),
                ),
            ]);

            if ($penalty['minutes'] > 0) {
                $transaction->logs()->create([
                    'user_id' => $user->id,
                    'action' => 'late_fee',
                    'description' => sprintf(
                        'Keterlambatan %s. Denda %s.',
                        $this->lateFee->describe($penalty['minutes']),
                        rupiah($penalty['fee']),
                    ),
                ]);
            }

            return $transaction->refresh();
        });
    }

    /**
     * Preview the penalty for the return form without saving anything.
     *
     * @return array{minutes: int, fee: int}
     */
    public function previewPenalty(Transaction $transaction, Carbon $actualReturnAt): array
    {
        return $this->lateFee->calculate($transaction->end_at, $actualReturnAt);
    }

    /**
     * Whether the inspected return condition shows major exterior/interior damage.
     */
    public function hasMajorDamage(array $data): bool
    {
        return $data['exterior_condition'] === ConditionLevel::Major->value
            || $data['interior_condition'] === ConditionLevel::Major->value;
    }
}

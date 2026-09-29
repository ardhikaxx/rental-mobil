<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\CarbonInterface;

class LateFeeCalculator
{
    /**
     * Calculate the late return penalty based on the configurable business
     * settings (grace period, calculation mode, and rate). No penalty values
     * are hard-coded.
     *
     * @return array{minutes: int, fee: int}
     */
    public function calculate(CarbonInterface $dueAt, CarbonInterface $actualReturnAt): array
    {
        if ($actualReturnAt->lte($dueAt)) {
            return ['minutes' => 0, 'fee' => 0];
        }

        $grace = max(0, Setting::getInt('late_fee_grace_minutes', 0));
        $minutesLate = (int) $dueAt->diffInMinutes($actualReturnAt) - $grace;

        if ($minutesLate <= 0) {
            return ['minutes' => 0, 'fee' => 0];
        }

        $rate = (int) round(Setting::getFloat('late_fee_rate', 0));
        $mode = Setting::getValue('late_fee_mode', 'per_hour');

        if ($rate <= 0) {
            return ['minutes' => $minutesLate, 'fee' => 0];
        }

        $units = $mode === 'per_day'
            ? (int) ceil($minutesLate / 1440)
            : (int) ceil($minutesLate / 60);

        return [
            'minutes' => $minutesLate,
            'fee' => $units * $rate,
        ];
    }

    public function describe(int $minutesLate): string
    {
        if ($minutesLate <= 0) {
            return 'Tepat waktu';
        }

        if ($minutesLate < 60) {
            return "{$minutesLate} menit terlambat";
        }

        if ($minutesLate < 1440) {
            $hours = (int) ceil($minutesLate / 60);

            return "{$hours} jam terlambat";
        }

        $days = (int) ceil($minutesLate / 1440);

        return "{$days} hari terlambat";
    }
}

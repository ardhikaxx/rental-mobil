<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class NumberGenerator
{
    /**
     * Generate a sequential, human readable, unique number such as
     * RNT-20260928-0001 inside the current database transaction.
     *
     * The caller is responsible for running this within a DB transaction so the
     * uniqueness can be enforced together with the record insert.
     */
    public function next(string $prefix, string $table, string $column, ?CarbonInterface $date = null): string
    {
        $date ??= now();
        $period = $date->format('Ymd');
        $base = sprintf('%s-%s', $prefix, $period);

        $attempt = 0;
        do {
            $count = (int) DB::table($table)
                ->where($column, 'like', $base.'-%')
                ->lockForUpdate()
                ->count();

            $number = sprintf('%s-%04d', $base, $count + 1);
            $exists = DB::table($table)->where($column, $number)->exists();
            $attempt++;
        } while ($exists && $attempt < 10);

        return $number;
    }

    public function transactionNumber(): string
    {
        return $this->next(
            (string) Setting::getValue('transaction_prefix', 'RNT'),
            'transactions',
            'transaction_number',
        );
    }

    public function paymentNumber(): string
    {
        return $this->next(
            (string) Setting::getValue('payment_prefix', 'PAY'),
            'payments',
            'payment_number',
        );
    }
}

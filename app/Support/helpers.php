<?php

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

if (! function_exists('rupiah')) {
    /**
     * Format an amount as Indonesian Rupiah, e.g. Rp 150.000.
     */
    function rupiah(int|float|string|null $amount): string
    {
        return 'Rp '.number_format((float) ($amount ?? 0), 0, ',', '.');
    }
}

if (! function_exists('tanggal')) {
    /**
     * Format a date/datetime as 28 Sep 2026.
     */
    function tanggal(CarbonInterface|string|null $date, string $format = 'd M Y'): string
    {
        if ($date === null || $date === '') {
            return '-';
        }

        $date = $date instanceof CarbonInterface ? $date : Carbon::parse($date);

        return $date->format($format);
    }
}

if (! function_exists('tanggal_waktu')) {
    /**
     * Format a date/datetime as 28 Sep 2026 14:30.
     */
    function tanggal_waktu(CarbonInterface|string|null $date): string
    {
        return tanggal($date, 'd M Y H:i');
    }
}

if (! function_exists('tanggal_panjang')) {
    /**
     * Format a date as Senin, 28 September 2026.
     */
    function tanggal_panjang(CarbonInterface|string|null $date): string
    {
        return tanggal($date, 'l, d F Y');
    }
}

if (! function_exists('jam')) {
    /**
     * Format a datetime as HH:MM.
     */
    function jam(CarbonInterface|string|null $date): string
    {
        return tanggal($date, 'H:i');
    }
}

if (! function_exists('durasi_rental')) {
    /**
     * Human readable duration between two datetimes.
     */
    function durasi_rental(CarbonInterface $start, CarbonInterface $end): string
    {
        $hours = $start->diffInHours($end);

        if ($hours < 24) {
            return "{$hours} jam";
        }

        $days = (int) ceil($hours / 24);

        return "{$days} hari";
    }
}

<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Pembayaran untuk setiap transaksi — ditulis eksplisit tanpa factory/faker/loop.
     *
     * Aturan bisnis yang dijaga:
     *  - Transaksi COMPLETED harus total pembayaran = total + late_fee.
     *  - Transaksi RENTED (aktif) cukup DP (min 20% dari total), sisanya belum lunas.
     *  - Transaksi BOOKED boleh hanya DP, atau lunas, atau belum ada pembayaran (awaiting).
     *  - Transaksi CANCELLED tidak mempunyai pelunasan (DP mungkin sudah masuk).
     *
     * Format nomor: PAY-YYYYMMDD-NNNN
     * method: cash | transfer | qris | e_wallet | other
     * type  : dp | cicilan | pelunasan | denda
     */
    public function run(): void
    {
        Payment::insert([

            // ── TRX 1 (RNT-20250107-0001) total 700.000 + denda 50.000 ────────
            ['id' => 1, 'payment_number' => 'PAY-20250106-0001', 'transaction_id' => 1,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-01-06', 'notes' => 'DP 50% via BRI', 'recorded_by' => 3,
                'created_at' => '2025-01-06 16:05:00', 'updated_at' => '2025-01-06 16:05:00'],
            ['id' => 2, 'payment_number' => 'PAY-20250109-0001', 'transaction_id' => 1,
                'amount' => 350000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-01-09', 'notes' => 'Pelunasan saat pengembalian', 'recorded_by' => 6,
                'created_at' => '2025-01-09 09:15:00', 'updated_at' => '2025-01-09 09:15:00'],
            ['id' => 3, 'payment_number' => 'PAY-20250109-0002', 'transaction_id' => 1,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-01-09', 'notes' => 'Denda terlambat 20 menit melewati masa tenggang', 'recorded_by' => 6,
                'created_at' => '2025-01-09 09:18:00', 'updated_at' => '2025-01-09 09:18:00'],

            // ── TRX 2 (RNT-20250110-0001) total 1.725.000 ────────────────────
            ['id' => 4, 'payment_number' => 'PAY-20250109-0003', 'transaction_id' => 2,
                'amount' => 700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-01-09', 'notes' => 'DP via BCA ~40%', 'recorded_by' => 3,
                'created_at' => '2025-01-09 15:35:00', 'updated_at' => '2025-01-09 15:35:00'],
            ['id' => 5, 'payment_number' => 'PAY-20250113-0001', 'transaction_id' => 2,
                'amount' => 1025000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-01-13', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-01-13 09:08:00', 'updated_at' => '2025-01-13 09:08:00'],

            // ── TRX 3 (RNT-20250114-0001) total 275.000, lunas langsung ──────
            ['id' => 6, 'payment_number' => 'PAY-20250113-0002', 'transaction_id' => 3,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-01-13', 'notes' => 'Lunas di muka', 'recorded_by' => 3,
                'created_at' => '2025-01-13 17:15:00', 'updated_at' => '2025-01-13 17:15:00'],

            // ── TRX 4 (RNT-20250119-0001) total 1.125.000 + denda 100.000 ───
            ['id' => 7, 'payment_number' => 'PAY-20250118-0001', 'transaction_id' => 4,
                'amount' => 450000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2025-01-18', 'notes' => 'DP 40% via QRIS', 'recorded_by' => 3,
                'created_at' => '2025-01-18 14:05:00', 'updated_at' => '2025-01-18 14:05:00'],
            ['id' => 8, 'payment_number' => 'PAY-20250122-0001', 'transaction_id' => 4,
                'amount' => 675000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-01-22', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-01-22 09:52:00', 'updated_at' => '2025-01-22 09:52:00'],
            ['id' => 9, 'payment_number' => 'PAY-20250122-0002', 'transaction_id' => 4,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-01-22', 'notes' => 'Terlambat 80 menit melewati masa tenggang (2 jam)', 'recorded_by' => 7,
                'created_at' => '2025-01-22 09:55:00', 'updated_at' => '2025-01-22 09:55:00'],

            // ── TRX 5 (RNT-20250124-0001) total 1.125.000 ────────────────────
            ['id' => 10, 'payment_number' => 'PAY-20250123-0001', 'transaction_id' => 5,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-01-23', 'notes' => 'DP transfer BRI', 'recorded_by' => 4,
                'created_at' => '2025-01-23 11:25:00', 'updated_at' => '2025-01-23 11:25:00'],
            ['id' => 11, 'payment_number' => 'PAY-20250127-0001', 'transaction_id' => 5,
                'amount' => 725000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-01-27', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-01-27 09:12:00', 'updated_at' => '2025-01-27 09:12:00'],

            // ── TRX 6 (RNT-20250203-0001) total 700.000 ──────────────────────
            ['id' => 12, 'payment_number' => 'PAY-20250202-0001', 'transaction_id' => 6,
                'amount' => 700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-02-02', 'notes' => 'Lunas di muka via BNI', 'recorded_by' => 3,
                'created_at' => '2025-02-02 10:05:00', 'updated_at' => '2025-02-02 10:05:00'],

            // ── TRX 7 (RNT-20250208-0001) total 2.500.000 + denda 100.000 ───
            ['id' => 13, 'payment_number' => 'PAY-20250207-0001', 'transaction_id' => 7,
                'amount' => 1000000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-02-07', 'notes' => 'DP 40% via BCA', 'recorded_by' => 4,
                'created_at' => '2025-02-07 14:35:00', 'updated_at' => '2025-02-07 14:35:00'],
            ['id' => 14, 'payment_number' => 'PAY-20250212-0001', 'transaction_id' => 7,
                'amount' => 1500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-02-12', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-02-12 10:57:00', 'updated_at' => '2025-02-12 10:57:00'],
            ['id' => 15, 'payment_number' => 'PAY-20250212-0002', 'transaction_id' => 7,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-02-12', 'notes' => 'Terlambat 85 menit melewati masa tenggang', 'recorded_by' => 6,
                'created_at' => '2025-02-12 11:00:00', 'updated_at' => '2025-02-12 11:00:00'],

            // ── TRX 8 (RNT-20250215-0001) total 1.725.000 ────────────────────
            ['id' => 16, 'payment_number' => 'PAY-20250214-0001', 'transaction_id' => 8,
                'amount' => 600000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-02-14', 'notes' => 'DP ~35%', 'recorded_by' => 3,
                'created_at' => '2025-02-14 15:05:00', 'updated_at' => '2025-02-14 15:05:00'],
            ['id' => 17, 'payment_number' => 'PAY-20250218-0001', 'transaction_id' => 8,
                'amount' => 1125000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-02-18', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-02-18 08:22:00', 'updated_at' => '2025-02-18 08:22:00'],

            // ── TRX 9 (RNT-20250222-0001) total 300.000, lunas langsung ──────
            ['id' => 18, 'payment_number' => 'PAY-20250222-0001', 'transaction_id' => 9,
                'amount' => 300000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-02-22', 'notes' => 'Lunas tunai', 'recorded_by' => 4,
                'created_at' => '2025-02-22 09:35:00', 'updated_at' => '2025-02-22 09:35:00'],

            // ── TRX 10 (RNT-20250226-0001) total 850.000 ─────────────────────
            ['id' => 19, 'payment_number' => 'PAY-20250225-0001', 'transaction_id' => 10,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-02-25', 'notes' => 'DP ~41%', 'recorded_by' => 3,
                'created_at' => '2025-02-25 13:05:00', 'updated_at' => '2025-02-25 13:05:00'],
            ['id' => 20, 'payment_number' => 'PAY-20250228-0001', 'transaction_id' => 10,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-02-28', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-02-28 09:12:00', 'updated_at' => '2025-02-28 09:12:00'],

            // ── TRX 11 (RNT-20250305-0001) total 275.000 ─────────────────────
            ['id' => 21, 'payment_number' => 'PAY-20250304-0001', 'transaction_id' => 11,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-03-04', 'notes' => 'Lunas tunai di muka', 'recorded_by' => 3,
                'created_at' => '2025-03-04 16:05:00', 'updated_at' => '2025-03-04 16:05:00'],

            // ── TRX 12 (RNT-20250310-0001) total 650.000 + denda 50.000 ─────
            ['id' => 22, 'payment_number' => 'PAY-20250309-0001', 'transaction_id' => 12,
                'amount' => 300000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2025-03-09', 'notes' => 'DP ~46%', 'recorded_by' => 4,
                'created_at' => '2025-03-09 10:05:00', 'updated_at' => '2025-03-09 10:05:00'],
            ['id' => 23, 'payment_number' => 'PAY-20250312-0001', 'transaction_id' => 12,
                'amount' => 350000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-03-12', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-03-12 10:32:00', 'updated_at' => '2025-03-12 10:32:00'],
            ['id' => 24, 'payment_number' => 'PAY-20250312-0002', 'transaction_id' => 12,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-03-12', 'notes' => 'Terlambat 80 menit', 'recorded_by' => 7,
                'created_at' => '2025-03-12 10:35:00', 'updated_at' => '2025-03-12 10:35:00'],

            // ── TRX 13 (RNT-20250317-0001) total 1.275.000 ───────────────────
            ['id' => 25, 'payment_number' => 'PAY-20250316-0001', 'transaction_id' => 13,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-03-16', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-03-16 14:35:00', 'updated_at' => '2025-03-16 14:35:00'],
            ['id' => 26, 'payment_number' => 'PAY-20250320-0001', 'transaction_id' => 13,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-03-20', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-03-20 08:12:00', 'updated_at' => '2025-03-20 08:12:00'],

            // ── TRX 14 (RNT-20250324-0001) total 1.300.000 ───────────────────
            ['id' => 27, 'payment_number' => 'PAY-20250323-0001', 'transaction_id' => 14,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-03-23', 'notes' => 'DP ~38%', 'recorded_by' => 4,
                'created_at' => '2025-03-23 11:05:00', 'updated_at' => '2025-03-23 11:05:00'],
            ['id' => 28, 'payment_number' => 'PAY-20250327-0001', 'transaction_id' => 14,
                'amount' => 800000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-03-27', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-03-27 09:17:00', 'updated_at' => '2025-03-27 09:17:00'],

            // ── TRX 15 (RNT-20250329-0001) total 275.000 ─────────────────────
            ['id' => 29, 'payment_number' => 'PAY-20250329-0001', 'transaction_id' => 15,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-03-29', 'notes' => 'Lunas tunai', 'recorded_by' => 3,
                'created_at' => '2025-03-29 09:45:00', 'updated_at' => '2025-03-29 09:45:00'],

            // ── TRX 16 (RNT-20250403-0001) total 1.400.000 + denda 50.000 ───
            ['id' => 30, 'payment_number' => 'PAY-20250402-0001', 'transaction_id' => 16,
                'amount' => 560000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-04-02', 'notes' => 'DP 40%', 'recorded_by' => 4,
                'created_at' => '2025-04-02 13:05:00', 'updated_at' => '2025-04-02 13:05:00'],
            ['id' => 31, 'payment_number' => 'PAY-20250407-0001', 'transaction_id' => 16,
                'amount' => 840000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-04-07', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-04-07 09:42:00', 'updated_at' => '2025-04-07 09:42:00'],
            ['id' => 32, 'payment_number' => 'PAY-20250407-0002', 'transaction_id' => 16,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-04-07', 'notes' => 'Terlambat 90 menit', 'recorded_by' => 7,
                'created_at' => '2025-04-07 09:45:00', 'updated_at' => '2025-04-07 09:45:00'],

            // ── TRX 17 (RNT-20250410-0001) total 700.000 ─────────────────────
            ['id' => 33, 'payment_number' => 'PAY-20250409-0001', 'transaction_id' => 17,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-04-09', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-04-09 14:05:00', 'updated_at' => '2025-04-09 14:05:00'],
            ['id' => 34, 'payment_number' => 'PAY-20250412-0001', 'transaction_id' => 17,
                'amount' => 400000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-04-12', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-04-12 09:07:00', 'updated_at' => '2025-04-12 09:07:00'],

            // ── TRX 18 (RNT-20250416-0001) total 1.425.000 ───────────────────
            ['id' => 35, 'payment_number' => 'PAY-20250415-0001', 'transaction_id' => 18,
                'amount' => 600000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-04-15', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-04-15 10:05:00', 'updated_at' => '2025-04-15 10:05:00'],
            ['id' => 36, 'payment_number' => 'PAY-20250419-0001', 'transaction_id' => 18,
                'amount' => 825000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-04-19', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-04-19 08:32:00', 'updated_at' => '2025-04-19 08:32:00'],

            // ── TRX 19 (RNT-20250425-0001) total 275.000 ─────────────────────
            ['id' => 37, 'payment_number' => 'PAY-20250425-0001', 'transaction_id' => 19,
                'amount' => 275000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2025-04-25', 'notes' => 'Lunas QRIS di tempat', 'recorded_by' => 3,
                'created_at' => '2025-04-25 09:35:00', 'updated_at' => '2025-04-25 09:35:00'],

            // ── TRX 20 (RNT-20250503-0001) total 2.875.000 ───────────────────
            ['id' => 38, 'payment_number' => 'PAY-20250502-0001', 'transaction_id' => 20,
                'amount' => 1150000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-05-02', 'notes' => 'DP 40%', 'recorded_by' => 4,
                'created_at' => '2025-05-02 15:05:00', 'updated_at' => '2025-05-02 15:05:00'],
            ['id' => 39, 'payment_number' => 'PAY-20250508-0001', 'transaction_id' => 20,
                'amount' => 1725000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-05-08', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-05-08 08:22:00', 'updated_at' => '2025-05-08 08:22:00'],

            // ── TRX 21 (RNT-20250506-0001) total 1.400.000 + denda 100.000 ──
            ['id' => 40, 'payment_number' => 'PAY-20250505-0001', 'transaction_id' => 21,
                'amount' => 600000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-05-05', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-05-05 13:05:00', 'updated_at' => '2025-05-05 13:05:00'],
            ['id' => 41, 'payment_number' => 'PAY-20250510-0001', 'transaction_id' => 21,
                'amount' => 800000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-05-10', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-05-10 11:32:00', 'updated_at' => '2025-05-10 11:32:00'],
            ['id' => 42, 'payment_number' => 'PAY-20250510-0002', 'transaction_id' => 21,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-05-10', 'notes' => 'Terlambat 140 menit', 'recorded_by' => 6,
                'created_at' => '2025-05-10 11:35:00', 'updated_at' => '2025-05-10 11:35:00'],

            // ── TRX 22 (RNT-20250514-0001) total 700.000 ─────────────────────
            ['id' => 43, 'payment_number' => 'PAY-20250513-0001', 'transaction_id' => 22,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-05-13', 'notes' => 'DP 50%', 'recorded_by' => 4,
                'created_at' => '2025-05-13 16:05:00', 'updated_at' => '2025-05-13 16:05:00'],
            ['id' => 44, 'payment_number' => 'PAY-20250516-0001', 'transaction_id' => 22,
                'amount' => 350000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-05-16', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-05-16 08:02:00', 'updated_at' => '2025-05-16 08:02:00'],

            // ── TRX 23 (RNT-20250521-0001) total 300.000 ─────────────────────
            ['id' => 45, 'payment_number' => 'PAY-20250520-0001', 'transaction_id' => 23,
                'amount' => 300000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-05-20', 'notes' => 'Lunas muka tunai', 'recorded_by' => 3,
                'created_at' => '2025-05-20 11:05:00', 'updated_at' => '2025-05-20 11:05:00'],

            // ── TRX 24 (RNT-20250528-0001) total 325.000 ─────────────────────
            ['id' => 46, 'payment_number' => 'PAY-20250528-0001', 'transaction_id' => 24,
                'amount' => 325000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2025-05-28', 'notes' => 'Lunas QRIS', 'recorded_by' => 4,
                'created_at' => '2025-05-28 09:35:00', 'updated_at' => '2025-05-28 09:35:00'],

            // ── TRX 25 (RNT-20250604-0001) total 1.275.000 ───────────────────
            ['id' => 47, 'payment_number' => 'PAY-20250603-0001', 'transaction_id' => 25,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-06-03', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-06-03 14:05:00', 'updated_at' => '2025-06-03 14:05:00'],
            ['id' => 48, 'payment_number' => 'PAY-20250607-0001', 'transaction_id' => 25,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-06-07', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-06-07 08:17:00', 'updated_at' => '2025-06-07 08:17:00'],

            // ── TRX 26 (RNT-20250609-0001) total 375.000 ─────────────────────
            ['id' => 49, 'payment_number' => 'PAY-20250609-0001', 'transaction_id' => 26,
                'amount' => 375000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-06-09', 'notes' => 'Lunas tunai', 'recorded_by' => 4,
                'created_at' => '2025-06-09 08:35:00', 'updated_at' => '2025-06-09 08:35:00'],

            // ── TRX 27 (RNT-20250616-0001) total 1.275.000 + denda 100.000 ──
            ['id' => 50, 'payment_number' => 'PAY-20250615-0001', 'transaction_id' => 27,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-06-15', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-06-15 10:05:00', 'updated_at' => '2025-06-15 10:05:00'],
            ['id' => 51, 'payment_number' => 'PAY-20250619-0001', 'transaction_id' => 27,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-06-19', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-06-19 10:02:00', 'updated_at' => '2025-06-19 10:02:00'],
            ['id' => 52, 'payment_number' => 'PAY-20250619-0002', 'transaction_id' => 27,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-06-19', 'notes' => 'Terlambat 110 menit', 'recorded_by' => 6,
                'created_at' => '2025-06-19 10:05:00', 'updated_at' => '2025-06-19 10:05:00'],

            // ── TRX 28 (RNT-20250623-0001) total 275.000 ─────────────────────
            ['id' => 53, 'payment_number' => 'PAY-20250623-0001', 'transaction_id' => 28,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-06-23', 'notes' => 'Lunas muka', 'recorded_by' => 4,
                'created_at' => '2025-06-23 09:35:00', 'updated_at' => '2025-06-23 09:35:00'],

            // ── TRX 29 (RNT-20250628-0001) total 2.800.000 ───────────────────
            ['id' => 54, 'payment_number' => 'PAY-20250627-0001', 'transaction_id' => 29,
                'amount' => 1000000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-06-27', 'notes' => 'DP ~36%', 'recorded_by' => 3,
                'created_at' => '2025-06-27 15:05:00', 'updated_at' => '2025-06-27 15:05:00'],
            ['id' => 55, 'payment_number' => 'PAY-20250705-0001', 'transaction_id' => 29,
                'amount' => 1800000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-07-05', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-07-05 08:12:00', 'updated_at' => '2025-07-05 08:12:00'],

            // ── TRX 30 (RNT-20250707-0001) total 900.000 ─────────────────────
            ['id' => 56, 'payment_number' => 'PAY-20250706-0001', 'transaction_id' => 30,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-07-06', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-07-06 10:05:00', 'updated_at' => '2025-07-06 10:05:00'],
            ['id' => 57, 'payment_number' => 'PAY-20250709-0001', 'transaction_id' => 30,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-07-09', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-07-09 09:12:00', 'updated_at' => '2025-07-09 09:12:00'],

            // ── TRX 31 (RNT-20250711-0001) total 1.725.000 ───────────────────
            ['id' => 58, 'payment_number' => 'PAY-20250710-0001', 'transaction_id' => 31,
                'amount' => 700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-07-10', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-07-10 14:05:00', 'updated_at' => '2025-07-10 14:05:00'],
            ['id' => 59, 'payment_number' => 'PAY-20250714-0001', 'transaction_id' => 31,
                'amount' => 1025000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-07-14', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-07-14 08:22:00', 'updated_at' => '2025-07-14 08:22:00'],

            // ── TRX 32 (RNT-20250717-0001) total 750.000 + denda 50.000 ─────
            ['id' => 60, 'payment_number' => 'PAY-20250716-0001', 'transaction_id' => 32,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-07-16', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-07-16 11:05:00', 'updated_at' => '2025-07-16 11:05:00'],
            ['id' => 61, 'payment_number' => 'PAY-20250719-0001', 'transaction_id' => 32,
                'amount' => 450000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-07-19', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-07-19 10:42:00', 'updated_at' => '2025-07-19 10:42:00'],
            ['id' => 62, 'payment_number' => 'PAY-20250719-0002', 'transaction_id' => 32,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-07-19', 'notes' => 'Terlambat 90 menit', 'recorded_by' => 7,
                'created_at' => '2025-07-19 10:45:00', 'updated_at' => '2025-07-19 10:45:00'],

            // ── TRX 33 (RNT-20250724-0001) total 275.000 ─────────────────────
            ['id' => 63, 'payment_number' => 'PAY-20250724-0001', 'transaction_id' => 33,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-07-24', 'notes' => 'Lunas tunai', 'recorded_by' => 3,
                'created_at' => '2025-07-24 09:35:00', 'updated_at' => '2025-07-24 09:35:00'],

            // ── TRX 34 (RNT-20250730-0001) total 2.800.000 ───────────────────
            ['id' => 64, 'payment_number' => 'PAY-20250729-0001', 'transaction_id' => 34,
                'amount' => 1000000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-07-29', 'notes' => 'DP sewa mingguan', 'recorded_by' => 4,
                'created_at' => '2025-07-29 16:05:00', 'updated_at' => '2025-07-29 16:05:00'],
            ['id' => 65, 'payment_number' => 'PAY-20250806-0001', 'transaction_id' => 34,
                'amount' => 1800000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-08-06', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-08-06 08:12:00', 'updated_at' => '2025-08-06 08:12:00'],

            // ── TRX 35 (RNT-20250804-0001) total 425.000 ─────────────────────
            ['id' => 66, 'payment_number' => 'PAY-20250804-0001', 'transaction_id' => 35,
                'amount' => 425000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2025-08-04', 'notes' => 'Lunas QRIS', 'recorded_by' => 3,
                'created_at' => '2025-08-04 09:35:00', 'updated_at' => '2025-08-04 09:35:00'],

            // ── TRX 36 (RNT-20250808-0001) total 1.050.000 ───────────────────
            ['id' => 67, 'payment_number' => 'PAY-20250807-0001', 'transaction_id' => 36,
                'amount' => 450000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-08-07', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-08-07 13:05:00', 'updated_at' => '2025-08-07 13:05:00'],
            ['id' => 68, 'payment_number' => 'PAY-20250811-0001', 'transaction_id' => 36,
                'amount' => 600000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-08-11', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-08-11 09:12:00', 'updated_at' => '2025-08-11 09:12:00'],

            // ── TRX 37 (RNT-20250815-0001) total 1.725.000 + denda 100.000 ──
            ['id' => 69, 'payment_number' => 'PAY-20250814-0001', 'transaction_id' => 37,
                'amount' => 700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-08-14', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-08-14 15:05:00', 'updated_at' => '2025-08-14 15:05:00'],
            ['id' => 70, 'payment_number' => 'PAY-20250818-0001', 'transaction_id' => 37,
                'amount' => 1025000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-08-18', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-08-18 10:02:00', 'updated_at' => '2025-08-18 10:02:00'],
            ['id' => 71, 'payment_number' => 'PAY-20250818-0002', 'transaction_id' => 37,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-08-18', 'notes' => 'Terlambat 110 menit', 'recorded_by' => 6,
                'created_at' => '2025-08-18 10:05:00', 'updated_at' => '2025-08-18 10:05:00'],

            // ── TRX 38 (RNT-20250820-0001) total 950.000 ─────────────────────
            ['id' => 72, 'payment_number' => 'PAY-20250820-0001', 'transaction_id' => 38,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-08-20', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2025-08-20 09:35:00', 'updated_at' => '2025-08-20 09:35:00'],
            ['id' => 73, 'payment_number' => 'PAY-20250822-0001', 'transaction_id' => 38,
                'amount' => 550000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-08-22', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-08-22 10:17:00', 'updated_at' => '2025-08-22 10:17:00'],

            // ── TRX 39 (RNT-20250826-0001) total 850.000 ─────────────────────
            ['id' => 74, 'payment_number' => 'PAY-20250825-0001', 'transaction_id' => 39,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-08-25', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-08-25 14:05:00', 'updated_at' => '2025-08-25 14:05:00'],
            ['id' => 75, 'payment_number' => 'PAY-20250828-0001', 'transaction_id' => 39,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-08-28', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-08-28 08:27:00', 'updated_at' => '2025-08-28 08:27:00'],

            // ── TRX 40 (RNT-20250903-0001) total 300.000 ─────────────────────
            ['id' => 76, 'payment_number' => 'PAY-20250903-0001', 'transaction_id' => 40,
                'amount' => 300000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-09-03', 'notes' => 'Lunas tunai', 'recorded_by' => 5,
                'created_at' => '2025-09-03 09:35:00', 'updated_at' => '2025-09-03 09:35:00'],

            // ── TRX 41 (RNT-20250909-0001) total 1.350.000 ───────────────────
            ['id' => 77, 'payment_number' => 'PAY-20250908-0001', 'transaction_id' => 41,
                'amount' => 550000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-09-08', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-09-08 13:05:00', 'updated_at' => '2025-09-08 13:05:00'],
            ['id' => 78, 'payment_number' => 'PAY-20250912-0001', 'transaction_id' => 41,
                'amount' => 800000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-09-12', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-09-12 09:12:00', 'updated_at' => '2025-09-12 09:12:00'],

            // ── TRX 42 (RNT-20250915-0001) total 1.050.000 ───────────────────
            ['id' => 79, 'payment_number' => 'PAY-20250914-0001', 'transaction_id' => 42,
                'amount' => 450000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-09-14', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2025-09-14 15:05:00', 'updated_at' => '2025-09-14 15:05:00'],
            ['id' => 80, 'payment_number' => 'PAY-20250918-0001', 'transaction_id' => 42,
                'amount' => 600000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-09-18', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-09-18 08:17:00', 'updated_at' => '2025-09-18 08:17:00'],

            // ── TRX 43 (RNT-20250921-0001) total 850.000 + denda 100.000 ─────
            ['id' => 81, 'payment_number' => 'PAY-20250920-0001', 'transaction_id' => 43,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-09-20', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2025-09-20 10:05:00', 'updated_at' => '2025-09-20 10:05:00'],
            ['id' => 82, 'payment_number' => 'PAY-20250923-0001', 'transaction_id' => 43,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-09-23', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-09-23 10:57:00', 'updated_at' => '2025-09-23 10:57:00'],
            ['id' => 83, 'payment_number' => 'PAY-20250923-0002', 'transaction_id' => 43,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-09-23', 'notes' => 'Terlambat 85 menit melewati masa tenggang', 'recorded_by' => 6,
                'created_at' => '2025-09-23 11:00:00', 'updated_at' => '2025-09-23 11:00:00'],

            // ── TRX 44 (RNT-20250927-0001) CANCELLED — belum ada serah terima ─
            // Tidak ada pembayaran (langsung dibatalkan sebelum DP)

            // ── TRX 45 (RNT-20251003-0001) total 850.000 ─────────────────────
            ['id' => 84, 'payment_number' => 'PAY-20251002-0001', 'transaction_id' => 45,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-10-02', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2025-10-02 14:05:00', 'updated_at' => '2025-10-02 14:05:00'],
            ['id' => 85, 'payment_number' => 'PAY-20251005-0001', 'transaction_id' => 45,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-10-05', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-10-05 09:22:00', 'updated_at' => '2025-10-05 09:22:00'],

            // ── TRX 46 (RNT-20251009-0001) total 2.800.000 ───────────────────
            ['id' => 86, 'payment_number' => 'PAY-20251008-0001', 'transaction_id' => 46,
                'amount' => 1000000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-10-08', 'notes' => 'DP sewa mingguan', 'recorded_by' => 3,
                'created_at' => '2025-10-08 15:05:00', 'updated_at' => '2025-10-08 15:05:00'],
            ['id' => 87, 'payment_number' => 'PAY-20251016-0001', 'transaction_id' => 46,
                'amount' => 1800000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-10-16', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-10-16 08:22:00', 'updated_at' => '2025-10-16 08:22:00'],

            // ── TRX 47 (RNT-20251016-0001) total 4.200.000 ───────────────────
            ['id' => 88, 'payment_number' => 'PAY-20251015-0001', 'transaction_id' => 47,
                'amount' => 1700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-10-15', 'notes' => 'DP ~40%', 'recorded_by' => 4,
                'created_at' => '2025-10-15 11:05:00', 'updated_at' => '2025-10-15 11:05:00'],
            ['id' => 89, 'payment_number' => 'PAY-20251023-0001', 'transaction_id' => 47,
                'amount' => 2500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-10-23', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-10-23 09:12:00', 'updated_at' => '2025-10-23 09:12:00'],

            // ── TRX 48 (RNT-20251024-0001) total 950.000 ─────────────────────
            ['id' => 90, 'payment_number' => 'PAY-20251024-0001', 'transaction_id' => 48,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-10-24', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2025-10-24 09:05:00', 'updated_at' => '2025-10-24 09:05:00'],
            ['id' => 91, 'payment_number' => 'PAY-20251026-0001', 'transaction_id' => 48,
                'amount' => 550000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-10-26', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-10-26 10:42:00', 'updated_at' => '2025-10-26 10:42:00'],

            // ── TRX 49 (RNT-20251104-0001) total 275.000 ─────────────────────
            ['id' => 92, 'payment_number' => 'PAY-20251104-0001', 'transaction_id' => 49,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2025-11-04', 'notes' => 'Lunas tunai', 'recorded_by' => 3,
                'created_at' => '2025-11-04 09:35:00', 'updated_at' => '2025-11-04 09:35:00'],

            // ── TRX 50 (RNT-20251110-0001) total 850.000 ─────────────────────
            ['id' => 93, 'payment_number' => 'PAY-20251109-0001', 'transaction_id' => 50,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-11-09', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-11-09 14:05:00', 'updated_at' => '2025-11-09 14:05:00'],
            ['id' => 94, 'payment_number' => 'PAY-20251112-0001', 'transaction_id' => 50,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-11-12', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-11-12 09:22:00', 'updated_at' => '2025-11-12 09:22:00'],

            // ── TRX 51 (RNT-20251118-0001) total 1.275.000 + denda 100.000 ──
            ['id' => 95, 'payment_number' => 'PAY-20251117-0001', 'transaction_id' => 51,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-11-17', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2025-11-17 15:05:00', 'updated_at' => '2025-11-17 15:05:00'],
            ['id' => 96, 'payment_number' => 'PAY-20251121-0001', 'transaction_id' => 51,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-11-21', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-11-21 09:47:00', 'updated_at' => '2025-11-21 09:47:00'],
            ['id' => 97, 'payment_number' => 'PAY-20251121-0002', 'transaction_id' => 51,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-11-21', 'notes' => 'Terlambat 95 menit', 'recorded_by' => 7,
                'created_at' => '2025-11-21 09:50:00', 'updated_at' => '2025-11-21 09:50:00'],

            // ── TRX 52 (RNT-20251125-0001) total 350.000 ─────────────────────
            ['id' => 98, 'payment_number' => 'PAY-20251125-0001', 'transaction_id' => 52,
                'amount' => 350000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2025-11-25', 'notes' => 'Lunas QRIS', 'recorded_by' => 3,
                'created_at' => '2025-11-25 08:35:00', 'updated_at' => '2025-11-25 08:35:00'],

            // ── TRX 53 (RNT-20251204-0001) total 1.275.000 ───────────────────
            ['id' => 99, 'payment_number' => 'PAY-20251203-0001', 'transaction_id' => 53,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-12-03', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-12-03 14:05:00', 'updated_at' => '2025-12-03 14:05:00'],
            ['id' => 100, 'payment_number' => 'PAY-20251207-0001', 'transaction_id' => 53,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-12-07', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-12-07 08:17:00', 'updated_at' => '2025-12-07 08:17:00'],

            // ── TRX 54 (RNT-20251210-0001) total 900.000 ─────────────────────
            ['id' => 101, 'payment_number' => 'PAY-20251209-0001', 'transaction_id' => 54,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-12-09', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2025-12-09 10:05:00', 'updated_at' => '2025-12-09 10:05:00'],
            ['id' => 102, 'payment_number' => 'PAY-20251212-0001', 'transaction_id' => 54,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-12-12', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-12-12 09:32:00', 'updated_at' => '2025-12-12 09:32:00'],

            // ── TRX 55 (RNT-20251218-0001) total 4.200.000 + denda 100.000 ──
            ['id' => 103, 'payment_number' => 'PAY-20251217-0001', 'transaction_id' => 55,
                'amount' => 1700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-12-17', 'notes' => 'DP 40%', 'recorded_by' => 3,
                'created_at' => '2025-12-17 14:05:00', 'updated_at' => '2025-12-17 14:05:00'],
            ['id' => 104, 'payment_number' => 'PAY-20251225-0001', 'transaction_id' => 55,
                'amount' => 2500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-12-25', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2025-12-25 09:52:00', 'updated_at' => '2025-12-25 09:52:00'],
            ['id' => 105, 'payment_number' => 'PAY-20251225-0002', 'transaction_id' => 55,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2025-12-25', 'notes' => 'Terlambat 100 menit', 'recorded_by' => 7,
                'created_at' => '2025-12-25 09:55:00', 'updated_at' => '2025-12-25 09:55:00'],

            // ── TRX 56 (RNT-20251224-0001) total 750.000 ─────────────────────
            ['id' => 106, 'payment_number' => 'PAY-20251223-0001', 'transaction_id' => 56,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-12-23', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2025-12-23 16:05:00', 'updated_at' => '2025-12-23 16:05:00'],
            ['id' => 107, 'payment_number' => 'PAY-20251226-0001', 'transaction_id' => 56,
                'amount' => 450000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2025-12-26', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2025-12-26 09:17:00', 'updated_at' => '2025-12-26 09:17:00'],

            // ── TRX 57 (RNT-20251228-0001) total 1.750.000 ───────────────────
            ['id' => 108, 'payment_number' => 'PAY-20251227-0001', 'transaction_id' => 57,
                'amount' => 700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2025-12-27', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2025-12-27 14:05:00', 'updated_at' => '2025-12-27 14:05:00'],
            ['id' => 109, 'payment_number' => 'PAY-20260102-0001', 'transaction_id' => 57,
                'amount' => 1050000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-01-02', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-01-02 10:32:00', 'updated_at' => '2026-01-02 10:32:00'],

            // ── TRX 58 (RNT-20260108-0001) total 550.000 ─────────────────────
            ['id' => 110, 'payment_number' => 'PAY-20260107-0001', 'transaction_id' => 58,
                'amount' => 250000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-01-07', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-01-07 15:05:00', 'updated_at' => '2026-01-07 15:05:00'],
            ['id' => 111, 'payment_number' => 'PAY-20260110-0001', 'transaction_id' => 58,
                'amount' => 300000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-01-10', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-01-10 09:17:00', 'updated_at' => '2026-01-10 09:17:00'],

            // ── TRX 59 (RNT-20260115-0001) total 700.000 + denda 100.000 ─────
            ['id' => 112, 'payment_number' => 'PAY-20260114-0001', 'transaction_id' => 59,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-01-14', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-01-14 14:05:00', 'updated_at' => '2026-01-14 14:05:00'],
            ['id' => 113, 'payment_number' => 'PAY-20260117-0001', 'transaction_id' => 59,
                'amount' => 400000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-01-17', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-01-17 10:27:00', 'updated_at' => '2026-01-17 10:27:00'],
            ['id' => 114, 'payment_number' => 'PAY-20260117-0002', 'transaction_id' => 59,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-01-17', 'notes' => 'Terlambat 135 menit', 'recorded_by' => 7,
                'created_at' => '2026-01-17 10:30:00', 'updated_at' => '2026-01-17 10:30:00'],

            // ── TRX 60 (RNT-20260122-0001) total 1.725.000 ───────────────────
            ['id' => 115, 'payment_number' => 'PAY-20260121-0001', 'transaction_id' => 60,
                'amount' => 700000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-01-21', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-01-21 11:05:00', 'updated_at' => '2026-01-21 11:05:00'],
            ['id' => 116, 'payment_number' => 'PAY-20260125-0001', 'transaction_id' => 60,
                'amount' => 1025000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-01-25', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-01-25 09:22:00', 'updated_at' => '2026-01-25 09:22:00'],

            // ── TRX 61 (RNT-20260128-0001) total 1.275.000 ───────────────────
            ['id' => 117, 'payment_number' => 'PAY-20260127-0001', 'transaction_id' => 61,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-01-27', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-01-27 14:05:00', 'updated_at' => '2026-01-27 14:05:00'],
            ['id' => 118, 'payment_number' => 'PAY-20260131-0001', 'transaction_id' => 61,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-01-31', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-01-31 08:17:00', 'updated_at' => '2026-01-31 08:17:00'],

            // ── TRX 62 (RNT-20260204-0001) total 950.000 ─────────────────────
            ['id' => 119, 'payment_number' => 'PAY-20260203-0001', 'transaction_id' => 62,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-02-03', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-02-03 15:05:00', 'updated_at' => '2026-02-03 15:05:00'],
            ['id' => 120, 'payment_number' => 'PAY-20260206-0001', 'transaction_id' => 62,
                'amount' => 550000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-02-06', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-02-06 09:07:00', 'updated_at' => '2026-02-06 09:07:00'],

            // ── TRX 63 (RNT-20260211-0001) total 300.000 ─────────────────────
            ['id' => 121, 'payment_number' => 'PAY-20260211-0001', 'transaction_id' => 63,
                'amount' => 300000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2026-02-11', 'notes' => 'Lunas tunai', 'recorded_by' => 5,
                'created_at' => '2026-02-11 09:35:00', 'updated_at' => '2026-02-11 09:35:00'],

            // ── TRX 64 (RNT-20260218-0001) total 1.950.000 + denda 100.000 ──
            ['id' => 122, 'payment_number' => 'PAY-20260217-0001', 'transaction_id' => 64,
                'amount' => 800000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-02-17', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-02-17 10:05:00', 'updated_at' => '2026-02-17 10:05:00'],
            ['id' => 123, 'payment_number' => 'PAY-20260221-0001', 'transaction_id' => 64,
                'amount' => 1150000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-02-21', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-02-21 10:02:00', 'updated_at' => '2026-02-21 10:02:00'],
            ['id' => 124, 'payment_number' => 'PAY-20260221-0002', 'transaction_id' => 64,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-02-21', 'notes' => 'Terlambat 110 menit', 'recorded_by' => 6,
                'created_at' => '2026-02-21 10:05:00', 'updated_at' => '2026-02-21 10:05:00'],

            // ── TRX 65 (RNT-20260225-0001) total 275.000 ─────────────────────
            ['id' => 125, 'payment_number' => 'PAY-20260225-0001', 'transaction_id' => 65,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2026-02-25', 'notes' => 'Lunas muka', 'recorded_by' => 4,
                'created_at' => '2026-02-25 09:35:00', 'updated_at' => '2026-02-25 09:35:00'],

            // ── TRX 66 (RNT-20260305-0001) total 700.000 ─────────────────────
            ['id' => 126, 'payment_number' => 'PAY-20260304-0001', 'transaction_id' => 66,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-03-04', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-03-04 13:05:00', 'updated_at' => '2026-03-04 13:05:00'],
            ['id' => 127, 'payment_number' => 'PAY-20260307-0001', 'transaction_id' => 66,
                'amount' => 400000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-03-07', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-03-07 09:12:00', 'updated_at' => '2026-03-07 09:12:00'],

            // ── TRX 67 (RNT-20260311-0001) total 1.500.000 ───────────────────
            ['id' => 128, 'payment_number' => 'PAY-20260310-0001', 'transaction_id' => 67,
                'amount' => 600000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-03-10', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-03-10 14:05:00', 'updated_at' => '2026-03-10 14:05:00'],
            ['id' => 129, 'payment_number' => 'PAY-20260314-0001', 'transaction_id' => 67,
                'amount' => 900000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-03-14', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-03-14 09:12:00', 'updated_at' => '2026-03-14 09:12:00'],

            // ── TRX 68 (RNT-20260318-0001) total 325.000 ─────────────────────
            ['id' => 130, 'payment_number' => 'PAY-20260318-0001', 'transaction_id' => 68,
                'amount' => 325000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2026-03-18', 'notes' => 'Lunas QRIS', 'recorded_by' => 4,
                'created_at' => '2026-03-18 09:35:00', 'updated_at' => '2026-03-18 09:35:00'],

            // ── TRX 69 (RNT-20260325-0001) total 750.000 + denda 100.000 ─────
            ['id' => 131, 'payment_number' => 'PAY-20260324-0001', 'transaction_id' => 69,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-03-24', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-03-24 15:05:00', 'updated_at' => '2026-03-24 15:05:00'],
            ['id' => 132, 'payment_number' => 'PAY-20260327-0001', 'transaction_id' => 69,
                'amount' => 450000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-03-27', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-03-27 09:57:00', 'updated_at' => '2026-03-27 09:57:00'],
            ['id' => 133, 'payment_number' => 'PAY-20260327-0002', 'transaction_id' => 69,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-03-27', 'notes' => 'Terlambat 85 menit melewati masa tenggang', 'recorded_by' => 7,
                'created_at' => '2026-03-27 10:00:00', 'updated_at' => '2026-03-27 10:00:00'],

            // ── TRX 70 (RNT-20260403-0001) total 850.000 ─────────────────────
            ['id' => 134, 'payment_number' => 'PAY-20260402-0001', 'transaction_id' => 70,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-04-02', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-04-02 10:05:00', 'updated_at' => '2026-04-02 10:05:00'],
            ['id' => 135, 'payment_number' => 'PAY-20260405-0001', 'transaction_id' => 70,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-04-05', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-04-05 09:17:00', 'updated_at' => '2026-04-05 09:17:00'],

            // ── TRX 71 (RNT-20260410-0001) total 1.150.000 ───────────────────
            ['id' => 136, 'payment_number' => 'PAY-20260409-0001', 'transaction_id' => 71,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-04-09', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-04-09 14:05:00', 'updated_at' => '2026-04-09 14:05:00'],
            ['id' => 137, 'payment_number' => 'PAY-20260412-0001', 'transaction_id' => 71,
                'amount' => 650000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-04-12', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-04-12 08:22:00', 'updated_at' => '2026-04-12 08:22:00'],

            // ── TRX 72 (RNT-20260417-0001) total 850.000 + denda 50.000 ─────
            ['id' => 138, 'payment_number' => 'PAY-20260416-0001', 'transaction_id' => 72,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-04-16', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-04-16 10:05:00', 'updated_at' => '2026-04-16 10:05:00'],
            ['id' => 139, 'payment_number' => 'PAY-20260419-0001', 'transaction_id' => 72,
                'amount' => 450000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-04-19', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-04-19 10:42:00', 'updated_at' => '2026-04-19 10:42:00'],
            ['id' => 140, 'payment_number' => 'PAY-20260419-0002', 'transaction_id' => 72,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-04-19', 'notes' => 'Terlambat 90 menit', 'recorded_by' => 6,
                'created_at' => '2026-04-19 10:45:00', 'updated_at' => '2026-04-19 10:45:00'],

            // ── TRX 73 (RNT-20260424-0001) total 350.000 ─────────────────────
            ['id' => 141, 'payment_number' => 'PAY-20260424-0001', 'transaction_id' => 73,
                'amount' => 350000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2026-04-24', 'notes' => 'Lunas tunai', 'recorded_by' => 3,
                'created_at' => '2026-04-24 09:35:00', 'updated_at' => '2026-04-24 09:35:00'],

            // ── TRX 74 (RNT-20260505-0001) total 700.000 ─────────────────────
            ['id' => 142, 'payment_number' => 'PAY-20260504-0001', 'transaction_id' => 74,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-05-04', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-05-04 13:05:00', 'updated_at' => '2026-05-04 13:05:00'],
            ['id' => 143, 'payment_number' => 'PAY-20260507-0001', 'transaction_id' => 74,
                'amount' => 400000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-05-07', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-05-07 09:12:00', 'updated_at' => '2026-05-07 09:12:00'],

            // ── TRX 75 (RNT-20260512-0001) total 2.500.000 + denda 50.000 ───
            ['id' => 144, 'payment_number' => 'PAY-20260511-0001', 'transaction_id' => 75,
                'amount' => 1000000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-05-11', 'notes' => 'DP 40%', 'recorded_by' => 5,
                'created_at' => '2026-05-11 14:05:00', 'updated_at' => '2026-05-11 14:05:00'],
            ['id' => 145, 'payment_number' => 'PAY-20260516-0001', 'transaction_id' => 75,
                'amount' => 1500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-05-16', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-05-16 09:42:00', 'updated_at' => '2026-05-16 09:42:00'],
            ['id' => 146, 'payment_number' => 'PAY-20260516-0002', 'transaction_id' => 75,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-05-16', 'notes' => 'Terlambat 90 menit', 'recorded_by' => 7,
                'created_at' => '2026-05-16 09:45:00', 'updated_at' => '2026-05-16 09:45:00'],

            // ── TRX 76 (RNT-20260519-0001) total 275.000 ─────────────────────
            ['id' => 147, 'payment_number' => 'PAY-20260519-0001', 'transaction_id' => 76,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2026-05-19', 'notes' => 'Lunas tunai', 'recorded_by' => 3,
                'created_at' => '2026-05-19 09:35:00', 'updated_at' => '2026-05-19 09:35:00'],

            // ── TRX 77 (RNT-20260526-0001) total 600.000 ─────────────────────
            ['id' => 148, 'payment_number' => 'PAY-20260525-0001', 'transaction_id' => 77,
                'amount' => 250000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-05-25', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-05-25 14:05:00', 'updated_at' => '2026-05-25 14:05:00'],
            ['id' => 149, 'payment_number' => 'PAY-20260528-0001', 'transaction_id' => 77,
                'amount' => 350000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-05-28', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-05-28 09:12:00', 'updated_at' => '2026-05-28 09:12:00'],

            // ── TRX 78 (RNT-20260604-0001) total 950.000 ─────────────────────
            ['id' => 150, 'payment_number' => 'PAY-20260603-0001', 'transaction_id' => 78,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-06-03', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-06-03 13:05:00', 'updated_at' => '2026-06-03 13:05:00'],
            ['id' => 151, 'payment_number' => 'PAY-20260606-0001', 'transaction_id' => 78,
                'amount' => 550000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-06-06', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-06-06 09:12:00', 'updated_at' => '2026-06-06 09:12:00'],

            // ── TRX 79 (RNT-20260611-0001) total 1.050.000 ───────────────────
            ['id' => 152, 'payment_number' => 'PAY-20260610-0001', 'transaction_id' => 79,
                'amount' => 450000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-06-10', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-06-10 15:05:00', 'updated_at' => '2026-06-10 15:05:00'],
            ['id' => 153, 'payment_number' => 'PAY-20260614-0001', 'transaction_id' => 79,
                'amount' => 600000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-06-14', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-06-14 08:22:00', 'updated_at' => '2026-06-14 08:22:00'],

            // ── TRX 80 (RNT-20260618-0001) total 850.000 ─────────────────────
            ['id' => 154, 'payment_number' => 'PAY-20260617-0001', 'transaction_id' => 80,
                'amount' => 400000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-06-17', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-06-17 10:05:00', 'updated_at' => '2026-06-17 10:05:00'],
            ['id' => 155, 'payment_number' => 'PAY-20260620-0001', 'transaction_id' => 80,
                'amount' => 450000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-06-20', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-06-20 09:17:00', 'updated_at' => '2026-06-20 09:17:00'],

            // ── TRX 81 (RNT-20260625-0001) total 2.800.000 ───────────────────
            ['id' => 156, 'payment_number' => 'PAY-20260624-0001', 'transaction_id' => 81,
                'amount' => 1100000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-06-24', 'notes' => 'DP sewa mingguan ~39%', 'recorded_by' => 5,
                'created_at' => '2026-06-24 15:05:00', 'updated_at' => '2026-06-24 15:05:00'],
            ['id' => 157, 'payment_number' => 'PAY-20260702-0001', 'transaction_id' => 81,
                'amount' => 1700000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-07-02', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-07-02 08:17:00', 'updated_at' => '2026-07-02 08:17:00'],

            // ── TRX 82 (RNT-20260707-0001) total 1.500.000 ───────────────────
            ['id' => 158, 'payment_number' => 'PAY-20260706-0001', 'transaction_id' => 82,
                'amount' => 600000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-07-06', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-07-06 10:05:00', 'updated_at' => '2026-07-06 10:05:00'],
            ['id' => 159, 'payment_number' => 'PAY-20260710-0001', 'transaction_id' => 82,
                'amount' => 900000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-07-10', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-07-10 09:17:00', 'updated_at' => '2026-07-10 09:17:00'],

            // ── TRX 83 (RNT-20260714-0001) total 2.100.000 ───────────────────
            ['id' => 160, 'payment_number' => 'PAY-20260713-0001', 'transaction_id' => 83,
                'amount' => 900000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-07-13', 'notes' => 'DP ~43%', 'recorded_by' => 4,
                'created_at' => '2026-07-13 14:05:00', 'updated_at' => '2026-07-13 14:05:00'],
            ['id' => 161, 'payment_number' => 'PAY-20260717-0001', 'transaction_id' => 83,
                'amount' => 1200000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-07-17', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-07-17 08:17:00', 'updated_at' => '2026-07-17 08:17:00'],

            // ── TRX 84 (RNT-20260721-0001) total 1.275.000 + denda 50.000 ───
            ['id' => 162, 'payment_number' => 'PAY-20260720-0001', 'transaction_id' => 84,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-07-20', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-07-20 10:05:00', 'updated_at' => '2026-07-20 10:05:00'],
            ['id' => 163, 'payment_number' => 'PAY-20260724-0001', 'transaction_id' => 84,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-07-24', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-07-24 10:37:00', 'updated_at' => '2026-07-24 10:37:00'],
            ['id' => 164, 'payment_number' => 'PAY-20260724-0002', 'transaction_id' => 84,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-07-24', 'notes' => 'Terlambat 85 menit', 'recorded_by' => 6,
                'created_at' => '2026-07-24 10:40:00', 'updated_at' => '2026-07-24 10:40:00'],

            // ── TRX 85 (RNT-20260728-0001) total 275.000 ─────────────────────
            ['id' => 165, 'payment_number' => 'PAY-20260728-0001', 'transaction_id' => 85,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2026-07-28', 'notes' => 'Lunas muka', 'recorded_by' => 3,
                'created_at' => '2026-07-28 09:35:00', 'updated_at' => '2026-07-28 09:35:00'],

            // ── TRX 86 (RNT-20260804-0001) total 600.000 ─────────────────────
            ['id' => 166, 'payment_number' => 'PAY-20260803-0001', 'transaction_id' => 86,
                'amount' => 250000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-08-03', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-08-03 13:05:00', 'updated_at' => '2026-08-03 13:05:00'],
            ['id' => 167, 'payment_number' => 'PAY-20260806-0001', 'transaction_id' => 86,
                'amount' => 350000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-08-06', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-08-06 09:12:00', 'updated_at' => '2026-08-06 09:12:00'],

            // ── TRX 87 (RNT-20260811-0001) total 700.000 + denda 50.000 ─────
            ['id' => 168, 'payment_number' => 'PAY-20260810-0001', 'transaction_id' => 87,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-08-10', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-08-10 15:05:00', 'updated_at' => '2026-08-10 15:05:00'],
            ['id' => 169, 'payment_number' => 'PAY-20260813-0001', 'transaction_id' => 87,
                'amount' => 400000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-08-13', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-08-13 10:47:00', 'updated_at' => '2026-08-13 10:47:00'],
            ['id' => 170, 'payment_number' => 'PAY-20260813-0002', 'transaction_id' => 87,
                'amount' => 50000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-08-13', 'notes' => 'Terlambat 95 menit', 'recorded_by' => 7,
                'created_at' => '2026-08-13 10:50:00', 'updated_at' => '2026-08-13 10:50:00'],

            // ── TRX 88 (RNT-20260818-0001) total 1.275.000 ───────────────────
            ['id' => 171, 'payment_number' => 'PAY-20260817-0001', 'transaction_id' => 88,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-08-17', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-08-17 14:05:00', 'updated_at' => '2026-08-17 14:05:00'],
            ['id' => 172, 'payment_number' => 'PAY-20260821-0001', 'transaction_id' => 88,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-08-21', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-08-21 08:22:00', 'updated_at' => '2026-08-21 08:22:00'],

            // ── TRX 89 (RNT-20260825-0001) total 1.275.000 ───────────────────
            ['id' => 173, 'payment_number' => 'PAY-20260824-0001', 'transaction_id' => 89,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-08-24', 'notes' => null, 'recorded_by' => 4,
                'created_at' => '2026-08-24 11:05:00', 'updated_at' => '2026-08-24 11:05:00'],
            ['id' => 174, 'payment_number' => 'PAY-20260828-0001', 'transaction_id' => 89,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-08-28', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-08-28 09:12:00', 'updated_at' => '2026-08-28 09:12:00'],

            // ── TRX 90 (RNT-20260902-0001) total 700.000 ─────────────────────
            ['id' => 175, 'payment_number' => 'PAY-20260901-0001', 'transaction_id' => 90,
                'amount' => 300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-01', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-09-01 14:05:00', 'updated_at' => '2026-09-01 14:05:00'],
            ['id' => 176, 'payment_number' => 'PAY-20260904-0001', 'transaction_id' => 90,
                'amount' => 400000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-09-04', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-09-04 09:17:00', 'updated_at' => '2026-09-04 09:17:00'],

            // ── TRX 91 (RNT-20260908-0001) total 1.275.000 + denda 100.000 ──
            ['id' => 177, 'payment_number' => 'PAY-20260907-0001', 'transaction_id' => 91,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-07', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-09-07 15:05:00', 'updated_at' => '2026-09-07 15:05:00'],
            ['id' => 178, 'payment_number' => 'PAY-20260911-0001', 'transaction_id' => 91,
                'amount' => 775000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-09-11', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-09-11 10:02:00', 'updated_at' => '2026-09-11 10:02:00'],
            ['id' => 179, 'payment_number' => 'PAY-20260911-0002', 'transaction_id' => 91,
                'amount' => 100000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-09-11', 'notes' => 'Terlambat 110 menit', 'recorded_by' => 7,
                'created_at' => '2026-09-11 10:05:00', 'updated_at' => '2026-09-11 10:05:00'],

            // ── TRX 92 (RNT-20260912-0001) total 275.000 ─────────────────────
            ['id' => 180, 'payment_number' => 'PAY-20260912-0001', 'transaction_id' => 92,
                'amount' => 275000, 'method' => 'cash', 'type' => 'dp',
                'paid_at' => '2026-09-12', 'notes' => 'Lunas muka', 'recorded_by' => 4,
                'created_at' => '2026-09-12 09:35:00', 'updated_at' => '2026-09-12 09:35:00'],

            // ── TRX 93 (RNT-20260916-0001) total 550.000 ─────────────────────
            ['id' => 181, 'payment_number' => 'PAY-20260915-0001', 'transaction_id' => 93,
                'amount' => 250000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-15', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-09-15 13:05:00', 'updated_at' => '2026-09-15 13:05:00'],
            ['id' => 182, 'payment_number' => 'PAY-20260918-0001', 'transaction_id' => 93,
                'amount' => 300000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-09-18', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-09-18 09:12:00', 'updated_at' => '2026-09-18 09:12:00'],

            // ── TRX 94 (RNT-20260918-0001) total 850.000 ─────────────────────
            ['id' => 183, 'payment_number' => 'PAY-20260917-0001', 'transaction_id' => 94,
                'amount' => 350000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-17', 'notes' => null, 'recorded_by' => 3,
                'created_at' => '2026-09-17 14:05:00', 'updated_at' => '2026-09-17 14:05:00'],
            ['id' => 184, 'payment_number' => 'PAY-20260920-0001', 'transaction_id' => 94,
                'amount' => 500000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-09-20', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-09-20 08:22:00', 'updated_at' => '2026-09-20 08:22:00'],

            // ── TRX 95 (RNT-20260924-0001) total 1.100.000 + denda 350.000 ──
            ['id' => 185, 'payment_number' => 'PAY-20260923-0001', 'transaction_id' => 95,
                'amount' => 450000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-23', 'notes' => 'DP ~41%', 'recorded_by' => 4,
                'created_at' => '2026-09-23 11:05:00', 'updated_at' => '2026-09-23 11:05:00'],
            ['id' => 186, 'payment_number' => 'PAY-20260928-0002', 'transaction_id' => 95,
                'amount' => 650000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-09-28', 'notes' => null, 'recorded_by' => 7,
                'created_at' => '2026-09-28 16:05:00', 'updated_at' => '2026-09-28 16:05:00'],
            ['id' => 187, 'payment_number' => 'PAY-20260928-0003', 'transaction_id' => 95,
                'amount' => 350000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-09-28', 'notes' => 'Terlambat 370 menit melewati masa tenggang (7 jam × Rp50.000)', 'recorded_by' => 7,
                'created_at' => '2026-09-28 16:08:00', 'updated_at' => '2026-09-28 16:08:00'],

            // ── TRX 96 (RNT-20260925-0001) total 1.200.000 + denda 450.000 ──
            ['id' => 188, 'payment_number' => 'PAY-20260924-0002', 'transaction_id' => 96,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-24', 'notes' => null, 'recorded_by' => 5,
                'created_at' => '2026-09-24 15:05:00', 'updated_at' => '2026-09-24 15:05:00'],
            ['id' => 189, 'payment_number' => 'PAY-20260928-0004', 'transaction_id' => 96,
                'amount' => 700000, 'method' => 'cash', 'type' => 'pelunasan',
                'paid_at' => '2026-09-28', 'notes' => null, 'recorded_by' => 6,
                'created_at' => '2026-09-28 16:42:00', 'updated_at' => '2026-09-28 16:42:00'],
            ['id' => 190, 'payment_number' => 'PAY-20260928-0005', 'transaction_id' => 96,
                'amount' => 450000, 'method' => 'cash', 'type' => 'denda',
                'paid_at' => '2026-09-28', 'notes' => 'Terlambat 490 menit melewati masa tenggang (9 jam × Rp50.000)', 'recorded_by' => 6,
                'created_at' => '2026-09-28 16:45:00', 'updated_at' => '2026-09-28 16:45:00'],

            // ── TRX AKTIF 97–103 — hanya DP (masih disewa) ───────────────────

            ['id' => 191, 'payment_number' => 'PAY-20260926-0001', 'transaction_id' => 97,
                'amount' => 600000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-26', 'notes' => 'DP 40%', 'recorded_by' => 3,
                'created_at' => '2026-09-26 14:05:00', 'updated_at' => '2026-09-26 14:05:00'],

            ['id' => 192, 'payment_number' => 'PAY-20260927-0001', 'transaction_id' => 98,
                'amount' => 1300000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-27', 'notes' => 'DP 40%', 'recorded_by' => 4,
                'created_at' => '2026-09-27 16:05:00', 'updated_at' => '2026-09-27 16:05:00'],

            ['id' => 193, 'payment_number' => 'PAY-20260926-0002', 'transaction_id' => 99,
                'amount' => 1000000, 'method' => 'qris', 'type' => 'dp',
                'paid_at' => '2026-09-26', 'notes' => 'DP 40% via QRIS', 'recorded_by' => 5,
                'created_at' => '2026-09-26 11:05:00', 'updated_at' => '2026-09-26 11:05:00'],

            ['id' => 194, 'payment_number' => 'PAY-20260925-0002', 'transaction_id' => 100,
                'amount' => 850000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-25', 'notes' => 'DP 40%', 'recorded_by' => 3,
                'created_at' => '2026-09-25 15:05:00', 'updated_at' => '2026-09-25 15:05:00'],

            ['id' => 195, 'payment_number' => 'PAY-20260925-0003', 'transaction_id' => 101,
                'amount' => 950000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-25', 'notes' => 'DP 40%', 'recorded_by' => 4,
                'created_at' => '2026-09-25 13:05:00', 'updated_at' => '2026-09-25 13:05:00'],

            ['id' => 196, 'payment_number' => 'PAY-20260924-0003', 'transaction_id' => 102,
                'amount' => 900000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-24', 'notes' => 'DP 40%', 'recorded_by' => 5,
                'created_at' => '2026-09-24 16:05:00', 'updated_at' => '2026-09-24 16:05:00'],

            ['id' => 197, 'payment_number' => 'PAY-20260926-0003', 'transaction_id' => 103,
                'amount' => 800000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-26', 'notes' => 'DP 40%', 'recorded_by' => 3,
                'created_at' => '2026-09-26 10:05:00', 'updated_at' => '2026-09-26 10:05:00'],

            // ── TRX BOOKED 104–107 — DP saja ─────────────────────────────────

            ['id' => 198, 'payment_number' => 'PAY-20260929-0001', 'transaction_id' => 104,
                'amount' => 500000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-29', 'notes' => 'DP booking', 'recorded_by' => 4,
                'created_at' => '2026-09-29 08:35:00', 'updated_at' => '2026-09-29 08:35:00'],

            ['id' => 199, 'payment_number' => 'PAY-20260929-0002', 'transaction_id' => 105,
                'amount' => 630000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-29', 'notes' => 'DP 40%', 'recorded_by' => 5,
                'created_at' => '2026-09-29 09:05:00', 'updated_at' => '2026-09-29 09:05:00'],

            ['id' => 200, 'payment_number' => 'PAY-20260929-0003', 'transaction_id' => 106,
                'amount' => 540000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-29', 'notes' => 'DP 40%', 'recorded_by' => 3,
                'created_at' => '2026-09-29 10:05:00', 'updated_at' => '2026-09-29 10:05:00'],

            ['id' => 201, 'payment_number' => 'PAY-20260929-0004', 'transaction_id' => 107,
                'amount' => 1000000, 'method' => 'transfer', 'type' => 'dp',
                'paid_at' => '2026-09-29', 'notes' => 'DP sewa mingguan 40%', 'recorded_by' => 4,
                'created_at' => '2026-09-29 11:05:00', 'updated_at' => '2026-09-29 11:05:00'],

        ]);
    }
}

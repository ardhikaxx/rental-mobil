<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('username', 'superadmin')->first();
        $admin = User::where('username', 'adminoper')->first();
        $staff = User::where('username', 'stafgarasi')->first();

        $entries = [
            [$owner, 'update', 'settings', 'Pengaturan bisnis dikonfigurasi saat inisialisasi sistem.'],
            [$owner, 'create', 'vehicles', 'Data armada kendaraan diimpor ke sistem.'],
            [$admin, 'create', 'customers', 'Data pelanggan diimpor ke sistem.'],
            [$admin, 'create', 'transactions', 'Transaksi rental dibuat untuk periode berjalan.'],
            [$staff, 'handover', 'transactions', 'Serah terima kendaraan dilakukan di garasi.'],
            [$staff, 'return', 'transactions', 'Pengembalian kendaraan diperiksa dan dicatat.'],
            [$admin, 'payment', 'payments', 'Pembayaran pelanggan dicatat pada transaksi aktif.'],
        ];

        foreach ($entries as $index => [$user, $activity, $module, $description]) {
            AuditLog::firstOrCreate(
                ['description' => $description],
                [
                    'user_id' => $user?->id,
                    'activity' => $activity,
                    'module' => $module,
                    'record_type' => null,
                    'record_id' => null,
                    'ip_address' => '127.0.0.1',
                    'created_at' => now()->subHours(48 - ($index * 6)),
                    'updated_at' => now()->subHours(48 - ($index * 6)),
                ],
            );
        }
    }
}

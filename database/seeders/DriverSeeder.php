<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Data driver / supir Jaya Trans Rental Mobil Jember.
     * Ditulis eksplisit tanpa faker/factory/looping.
     */
    public function run(): void
    {
        Driver::insert([
            [
                'id' => 1,
                'code' => 'DRV-001',
                'name' => 'Joko Susilo',
                'phone' => '081234567801',
                'sim_number' => '920812345678',
                'sim_type' => 'SIM B1 Umum',
                'daily_rate' => 175000,
                'status' => 'available',
                'photo' => null,
                'notes' => 'Berpengalaman 12 tahun rute Jawa Timur – Bali. Menguasai mobil matic dan manual.',
                'is_active' => true,
                'created_at' => '2025-01-02 08:00:00',
                'updated_at' => '2026-09-29 08:00:00',
            ],
            [
                'id' => 2,
                'code' => 'DRV-002',
                'name' => 'Hendra Setiawan',
                'phone' => '081345678902',
                'sim_number' => '940523456789',
                'sim_type' => 'SIM A',
                'daily_rate' => 150000,
                'status' => 'busy',
                'photo' => null,
                'notes' => 'Sering melayani rute wisata Bromo, Ijen, dan Malang Batu.',
                'is_active' => true,
                'created_at' => '2025-01-02 08:00:00',
                'updated_at' => '2026-09-27 09:00:00',
            ],
            [
                'id' => 3,
                'code' => 'DRV-003',
                'name' => 'Agus Priyanto',
                'phone' => '085712345603',
                'sim_number' => '910334567890',
                'sim_type' => 'SIM A',
                'daily_rate' => 150000,
                'status' => 'available',
                'photo' => null,
                'notes' => 'Ramah, menguasai bahasa Inggris dasar untuk turis mancanegara.',
                'is_active' => true,
                'created_at' => '2025-02-10 09:00:00',
                'updated_at' => '2026-09-29 08:00:00',
            ],
            [
                'id' => 4,
                'code' => 'DRV-004',
                'name' => 'Bambang Sujarwo',
                'phone' => '087890123404',
                'sim_number' => '880745678901',
                'sim_type' => 'SIM B1',
                'daily_rate' => 175000,
                'status' => 'available',
                'photo' => null,
                'notes' => 'Spesialis perjalanan dinas VIP dan instansi pemerintahan.',
                'is_active' => true,
                'created_at' => '2025-03-01 08:30:00',
                'updated_at' => '2026-09-29 08:00:00',
            ],
            [
                'id' => 5,
                'code' => 'DRV-005',
                'name' => 'Dani Kurniawan',
                'phone' => '082156789005',
                'sim_number' => '970956789012',
                'sim_type' => 'SIM A',
                'daily_rate' => 150000,
                'status' => 'available',
                'photo' => null,
                'notes' => 'Hafal rute perkotaan Jember, Surabaya, Sidoarjo, dan Pasuruan.',
                'is_active' => true,
                'created_at' => '2025-04-15 10:00:00',
                'updated_at' => '2026-09-29 08:00:00',
            ],
            [
                'id' => 6,
                'code' => 'DRV-006',
                'name' => 'Rudi Hartono',
                'phone' => '089678123406',
                'sim_number' => '850167890123',
                'sim_type' => 'SIM A',
                'daily_rate' => 150000,
                'status' => 'off',
                'photo' => null,
                'notes' => 'Sedang cuti mingguan.',
                'is_active' => true,
                'created_at' => '2025-05-01 08:00:00',
                'updated_at' => '2026-09-29 08:00:00',
            ],
        ]);
    }
}

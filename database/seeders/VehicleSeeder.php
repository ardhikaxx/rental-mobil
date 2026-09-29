<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Armada Jaya Trans Rental Mobil — 30 unit (29 unit aktif + 1 unit dijual).
     *
     * Odometer di bawah adalah catatan per 29 September 2026 dan konsisten
     * dengan seluruh riwayat transaksi, pemeriksaan, dan perawatan pada seeder
     * lainnya. Nomor rangka & mesin bersifat sintetis (development only).
     */
    public function run(): void
    {
        Vehicle::insert([
            [
                'id' => 1, 'code' => 'VT-001', 'brand' => 'Toyota', 'model' => 'Avanza 1.3 G', 'type' => 'mpv', 'year' => 2022,
                'color' => 'Putih', 'license_plate' => 'P 1842 UD', 'chassis_number' => 'MHKM1BA3JNK018472', 'engine_number' => '1NRF0928473',
                'daily_rate' => 350000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 142386, 'fuel_level' => 'three_quarters',
                'notes' => 'Unit favorit pelanggan keluarga; servis rutin tiap 10.000 km.', 'is_active' => true,
                'created_at' => '2025-01-05 09:00:00', 'updated_at' => '2026-09-20 16:12:00', 'deleted_at' => null,
            ],
            [
                'id' => 2, 'code' => 'VT-002', 'brand' => 'Toyota', 'model' => 'Avanza 1.3 G', 'type' => 'mpv', 'year' => 2023,
                'color' => 'Hitam', 'license_plate' => 'P 1957 UE', 'chassis_number' => 'MHKM1BA3JNK024915', 'engine_number' => '1NRF1136402',
                'daily_rate' => 375000, 'photo' => null, 'status' => 'disewa', 'odometer' => 118204, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-05 09:10:00', 'updated_at' => '2026-09-27 08:35:00', 'deleted_at' => null,
            ],
            [
                'id' => 3, 'code' => 'VT-003', 'brand' => 'Toyota', 'model' => 'Veloz 1.5 Q CVT', 'type' => 'mpv', 'year' => 2023,
                'color' => 'Silver', 'license_plate' => 'P 1620 UF', 'chassis_number' => 'MHKM1BA3JNK031058', 'engine_number' => '2NRF0831926',
                'daily_rate' => 425000, 'photo' => null, 'status' => 'siap_jalan', 'odometer' => 96573, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-05 09:20:00', 'updated_at' => '2026-09-28 18:10:00', 'deleted_at' => null,
            ],
            [
                'id' => 4, 'code' => 'VT-004', 'brand' => 'Toyota', 'model' => 'Innova Reborn 2.0 V', 'type' => 'mpv', 'year' => 2021,
                'color' => 'Hitam', 'license_plate' => 'P 1338 UB', 'chassis_number' => 'MHFXW42G8M0127734', 'engine_number' => '1TRF0619285',
                'daily_rate' => 575000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 187940, 'fuel_level' => 'three_quarters',
                'notes' => 'Sering dipakai tamu dinas dan perjalanan luar kota.', 'is_active' => true,
                'created_at' => '2025-01-08 10:00:00', 'updated_at' => '2026-09-15 11:25:00', 'deleted_at' => null,
            ],
            [
                'id' => 5, 'code' => 'VT-005', 'brand' => 'Toyota', 'model' => 'Innova Zenix 2.0 G', 'type' => 'mpv', 'year' => 2023,
                'color' => 'Putih Mutiara', 'license_plate' => 'P 1712 UG', 'chassis_number' => 'MHFXW62G3P0084521', 'engine_number' => 'M20AF0347186',
                'daily_rate' => 650000, 'photo' => null, 'status' => 'disewa', 'odometer' => 88317, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-02-14 09:30:00', 'updated_at' => '2026-09-28 07:50:00', 'deleted_at' => null,
            ],
            [
                'id' => 6, 'code' => 'VT-006', 'brand' => 'Toyota', 'model' => 'Calya 1.2 G', 'type' => 'mpv', 'year' => 2022,
                'color' => 'Merah', 'license_plate' => 'N 1685 AB', 'chassis_number' => 'MHKA1BA3JNK017340', 'engine_number' => '3NRF0729154',
                'daily_rate' => 300000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 121552, 'fuel_level' => 'half',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-08 10:15:00', 'updated_at' => '2026-09-12 14:05:00', 'deleted_at' => null,
            ],
            [
                'id' => 7, 'code' => 'VT-007', 'brand' => 'Toyota', 'model' => 'Rush 1.5 S GR Sport', 'type' => 'suv', 'year' => 2022,
                'color' => 'Putih', 'license_plate' => 'P 1443 UC', 'chassis_number' => 'MHFC1BA3JNK029156', 'engine_number' => '2NRF0640837',
                'daily_rate' => 425000, 'photo' => null, 'status' => 'dibooking', 'odometer' => 133086, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-08 10:30:00', 'updated_at' => '2026-09-26 09:40:00', 'deleted_at' => null,
            ],
            [
                'id' => 8, 'code' => 'VT-008', 'brand' => 'Toyota', 'model' => 'Raize 1.0 Turbo GR Sport', 'type' => 'suv', 'year' => 2023,
                'color' => 'Kuning', 'license_plate' => 'P 1889 UH', 'chassis_number' => 'MHFC1BA3JNK033821', 'engine_number' => '1KRF0518462',
                'daily_rate' => 500000, 'photo' => null, 'status' => 'disewa', 'odometer' => 71248, 'fuel_level' => 'three_quarters',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-02-14 09:45:00', 'updated_at' => '2026-09-27 08:05:00', 'deleted_at' => null,
            ],
            [
                'id' => 9, 'code' => 'VT-009', 'brand' => 'Toyota', 'model' => 'Agya 1.2 G', 'type' => 'hatchback', 'year' => 2021,
                'color' => 'Putih', 'license_plate' => 'P 1264 UA', 'chassis_number' => 'MHKA1BA2JMK014923', 'engine_number' => '1NRF0716284',
                'daily_rate' => 275000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 156713, 'fuel_level' => 'half',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-08 10:45:00', 'updated_at' => '2026-09-18 15:20:00', 'deleted_at' => null,
            ],
            [
                'id' => 10, 'code' => 'VT-010', 'brand' => 'Toyota', 'model' => 'Yaris 1.5 G CVT', 'type' => 'hatchback', 'year' => 2022,
                'color' => 'Merah', 'license_plate' => 'L 1043 UZ', 'chassis_number' => 'MHFKB1BA3NK046190', 'engine_number' => '2NRF0827431',
                'daily_rate' => 350000, 'photo' => null, 'status' => 'perawatan', 'odometer' => 104925, 'fuel_level' => 'quarter',
                'notes' => 'Masuk bengkel 26 September 2026: ganti kampas rem depan.', 'is_active' => true,
                'created_at' => '2025-03-20 11:00:00', 'updated_at' => '2026-09-26 09:10:00', 'deleted_at' => null,
            ],
            [
                'id' => 11, 'code' => 'VT-011', 'brand' => 'Daihatsu', 'model' => 'Xenia 1.3 R', 'type' => 'mpv', 'year' => 2022,
                'color' => 'Silver', 'license_plate' => 'P 1738 UD', 'chassis_number' => 'MHKS3EJ4JNK027685', 'engine_number' => '1NRF0941728',
                'daily_rate' => 350000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 129640, 'fuel_level' => 'three_quarters',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-11 09:00:00', 'updated_at' => '2026-09-22 13:35:00', 'deleted_at' => null,
            ],
            [
                'id' => 12, 'code' => 'VT-012', 'brand' => 'Daihatsu', 'model' => 'Terios 1.5 R', 'type' => 'suv', 'year' => 2021,
                'color' => 'Hitam', 'license_plate' => 'N 1417 UB', 'chassis_number' => 'MHFC1BA2JMK022190', 'engine_number' => '2NRF0781562',
                'daily_rate' => 425000, 'photo' => null, 'status' => 'disewa', 'odometer' => 172381, 'fuel_level' => 'full',
                'notes' => 'Andalan untuk medan luar kota dan jalur selatan Jember.', 'is_active' => true,
                'created_at' => '2025-01-11 09:15:00', 'updated_at' => '2026-09-26 07:45:00', 'deleted_at' => null,
            ],
            [
                'id' => 13, 'code' => 'VT-013', 'brand' => 'Daihatsu', 'model' => 'Sigra 1.0 X', 'type' => 'mpv', 'year' => 2023,
                'color' => 'Putih', 'license_plate' => 'P 1584 UE', 'chassis_number' => 'MHKS5EJ4JNK031764', 'engine_number' => '1KRF0752918',
                'daily_rate' => 275000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 94126, 'fuel_level' => 'half',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-11 09:30:00', 'updated_at' => '2026-09-19 10:05:00', 'deleted_at' => null,
            ],
            [
                'id' => 14, 'code' => 'VT-014', 'brand' => 'Daihatsu', 'model' => 'Ayla 1.2 R', 'type' => 'hatchback', 'year' => 2024,
                'color' => 'Merah', 'license_plate' => 'P 1902 UJ', 'chassis_number' => 'MHKS5EJ4JNK038412', 'engine_number' => '1KRF1063847',
                'daily_rate' => 275000, 'photo' => null, 'status' => 'dibersihkan', 'odometer' => 46738, 'fuel_level' => 'quarter',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-08-16 09:00:00', 'updated_at' => '2026-09-28 16:25:00', 'deleted_at' => null,
            ],
            [
                'id' => 15, 'code' => 'VT-015', 'brand' => 'Honda', 'model' => 'Brio RS CVT', 'type' => 'hatchback', 'year' => 2022,
                'color' => 'Kuning', 'license_plate' => 'L 1229 UY', 'chassis_number' => 'MRHGM5H3JNK041287', 'engine_number' => 'L12B3140729',
                'daily_rate' => 325000, 'photo' => null, 'status' => 'siap_jalan', 'odometer' => 112864, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-02-20 10:00:00', 'updated_at' => '2026-09-28 17:55:00', 'deleted_at' => null,
            ],
            [
                'id' => 16, 'code' => 'VT-016', 'brand' => 'Honda', 'model' => 'Mobilio 1.5 E', 'type' => 'mpv', 'year' => 2021,
                'color' => 'Abu-abu', 'license_plate' => 'P 1386 UA', 'chassis_number' => 'MRHGM2H2JMK029513', 'engine_number' => 'R18A9810372',
                'daily_rate' => 375000, 'photo' => null, 'status' => 'perawatan', 'odometer' => 164209, 'fuel_level' => 'half',
                'notes' => 'Masuk bengkel 24 September 2026: tune up dan servis AC.', 'is_active' => true,
                'created_at' => '2025-01-15 09:30:00', 'updated_at' => '2026-09-24 08:35:00', 'deleted_at' => null,
            ],
            [
                'id' => 17, 'code' => 'VT-017', 'brand' => 'Honda', 'model' => 'BR-V 1.5 Prestige', 'type' => 'suv', 'year' => 2022,
                'color' => 'Putih', 'license_plate' => 'P 1495 UC', 'chassis_number' => 'MRHGH2H3JNK035186', 'engine_number' => 'L15Z1084723',
                'daily_rate' => 475000, 'photo' => null, 'status' => 'disewa', 'odometer' => 121738, 'fuel_level' => 'three_quarters',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-15 09:40:00', 'updated_at' => '2026-09-26 08:15:00', 'deleted_at' => null,
            ],
            [
                'id' => 18, 'code' => 'VT-018', 'brand' => 'Honda', 'model' => 'HR-V 1.5 SE', 'type' => 'suv', 'year' => 2023,
                'color' => 'Merah', 'license_plate' => 'L 1673 UN', 'chassis_number' => 'MRHRU5H3JNK017294', 'engine_number' => 'L15ZF0531926',
                'daily_rate' => 525000, 'photo' => null, 'status' => 'dibooking', 'odometer' => 79382, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-03-08 10:20:00', 'updated_at' => '2026-09-27 09:05:00', 'deleted_at' => null,
            ],
            [
                'id' => 19, 'code' => 'VT-019', 'brand' => 'Mitsubishi', 'model' => 'Xpander 1.5 Ultimate', 'type' => 'mpv', 'year' => 2022,
                'color' => 'Putih', 'license_plate' => 'P 1528 UE', 'chassis_number' => 'MMBJNKL10NK027491', 'engine_number' => '4A91J0813726',
                'daily_rate' => 450000, 'photo' => null, 'status' => 'dibooking', 'odometer' => 118453, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-18 09:15:00', 'updated_at' => '2026-09-28 10:40:00', 'deleted_at' => null,
            ],
            [
                'id' => 20, 'code' => 'VT-020', 'brand' => 'Mitsubishi', 'model' => 'Xpander Cross 1.5', 'type' => 'suv', 'year' => 2023,
                'color' => 'Silver', 'license_plate' => 'P 1811 UK', 'chassis_number' => 'MMBJNKL10PK031564', 'engine_number' => '4A91J1074392',
                'daily_rate' => 500000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 84517, 'fuel_level' => 'half',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-04-16 10:45:00', 'updated_at' => '2026-09-21 12:30:00', 'deleted_at' => null,
            ],
            [
                'id' => 21, 'code' => 'VT-021', 'brand' => 'Suzuki', 'model' => 'Ertiga 1.5 GX', 'type' => 'mpv', 'year' => 2021,
                'color' => 'Putih', 'license_plate' => 'P 1301 UA', 'chassis_number' => 'MHYEN2K3JMK018372', 'engine_number' => 'K15BJ0629184',
                'daily_rate' => 375000, 'photo' => null, 'status' => 'disewa', 'odometer' => 158326, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-18 09:30:00', 'updated_at' => '2026-09-25 07:50:00', 'deleted_at' => null,
            ],
            [
                'id' => 22, 'code' => 'VT-022', 'brand' => 'Suzuki', 'model' => 'XL7 1.5 Alpha', 'type' => 'suv', 'year' => 2022,
                'color' => 'Biru', 'license_plate' => 'P 1462 UD', 'chassis_number' => 'MHYEN2K3JNK029461', 'engine_number' => 'K15BJ0914726',
                'daily_rate' => 425000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 126904, 'fuel_level' => 'three_quarters',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-01-18 09:45:00', 'updated_at' => '2026-09-14 09:20:00', 'deleted_at' => null,
            ],
            [
                'id' => 23, 'code' => 'VT-023', 'brand' => 'Suzuki', 'model' => 'Ignis 1.2 GX', 'type' => 'hatchback', 'year' => 2021,
                'color' => 'Orange', 'license_plate' => 'N 1550 UM', 'chassis_number' => 'MHYFF2K2JMK013948', 'engine_number' => 'K12MJ0418726',
                'daily_rate' => 300000, 'photo' => null, 'status' => 'perawatan', 'odometer' => 141583, 'fuel_level' => 'quarter',
                'notes' => 'Masuk bengkel 22 September 2026: kompresor AC dan kaki-kaki.', 'is_active' => true,
                'created_at' => '2025-05-05 10:10:00', 'updated_at' => '2026-09-22 08:25:00', 'deleted_at' => null,
            ],
            [
                'id' => 24, 'code' => 'VT-024', 'brand' => 'Toyota', 'model' => 'Innova Zenix 2.0 V HV', 'type' => 'mpv', 'year' => 2024,
                'color' => 'Hitam', 'license_plate' => 'P 2018 UL', 'chassis_number' => 'MHFXW62G3R0049183', 'engine_number' => 'M20AF0714395',
                'daily_rate' => 700000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 52641, 'fuel_level' => 'full',
                'notes' => 'Unit premium untuk tamu instansi dan VIP.', 'is_active' => true,
                'created_at' => '2026-02-03 09:20:00', 'updated_at' => '2026-09-23 11:05:00', 'deleted_at' => null,
            ],
            [
                'id' => 25, 'code' => 'VT-025', 'brand' => 'Toyota', 'model' => 'Avanza 1.5 G CVT', 'type' => 'mpv', 'year' => 2024,
                'color' => 'Silver', 'license_plate' => 'P 1976 UM', 'chassis_number' => 'MHKM1BA3JRK029418', 'engine_number' => '2NRF1240837',
                'daily_rate' => 400000, 'photo' => null, 'status' => 'dibersihkan', 'odometer' => 58913, 'fuel_level' => 'quarter',
                'notes' => null, 'is_active' => true,
                'created_at' => '2026-02-03 09:35:00', 'updated_at' => '2026-09-28 16:40:00', 'deleted_at' => null,
            ],
            [
                'id' => 26, 'code' => 'VT-026', 'brand' => 'Toyota', 'model' => 'Yaris Cross 1.5 HEV', 'type' => 'suv', 'year' => 2024,
                'color' => 'Putih', 'license_plate' => 'P 2116 UN', 'chassis_number' => 'MHFKB1BA3RK051827', 'engine_number' => 'M15AF0439182',
                'daily_rate' => 550000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 44078, 'fuel_level' => 'half',
                'notes' => 'Unit hybrid; tarif sudah termasuk perawatan baterai.', 'is_active' => true,
                'created_at' => '2026-05-12 10:00:00', 'updated_at' => '2026-09-17 13:45:00', 'deleted_at' => null,
            ],
            [
                'id' => 27, 'code' => 'VT-027', 'brand' => 'Honda', 'model' => 'Mobilio RS', 'type' => 'mpv', 'year' => 2024,
                'color' => 'Abu-abu', 'license_plate' => 'W 1745 UQ', 'chassis_number' => 'MRHGM2H2JRK034761', 'engine_number' => 'L15Z1173094',
                'daily_rate' => 400000, 'photo' => null, 'status' => 'disewa', 'odometer' => 49327, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2026-01-20 09:15:00', 'updated_at' => '2026-09-27 07:40:00', 'deleted_at' => null,
            ],
            [
                'id' => 28, 'code' => 'VT-028', 'brand' => 'Toyota', 'model' => 'Calya 1.2 G', 'type' => 'mpv', 'year' => 2025,
                'color' => 'Putih', 'license_plate' => 'P 2240 UR', 'chassis_number' => 'MHKA1BA3JSK019238', 'engine_number' => '3NRF1093726',
                'daily_rate' => 325000, 'photo' => null, 'status' => 'tersedia', 'odometer' => 28416, 'fuel_level' => 'three_quarters',
                'notes' => null, 'is_active' => true,
                'created_at' => '2025-11-04 09:30:00', 'updated_at' => '2026-09-13 10:55:00', 'deleted_at' => null,
            ],
            [
                'id' => 29, 'code' => 'VT-029', 'brand' => 'Daihatsu', 'model' => 'Xenia 1.5 R CVT', 'type' => 'mpv', 'year' => 2025,
                'color' => 'Hitam', 'license_plate' => 'P 2317 US', 'chassis_number' => 'MHKS3EJ4JSK042173', 'engine_number' => '2NRF1314759',
                'daily_rate' => 375000, 'photo' => null, 'status' => 'dibooking', 'odometer' => 24905, 'fuel_level' => 'full',
                'notes' => null, 'is_active' => true,
                'created_at' => '2026-03-02 09:45:00', 'updated_at' => '2026-09-28 14:20:00', 'deleted_at' => null,
            ],
            [
                'id' => 30, 'code' => 'VT-030', 'brand' => 'Daihatsu', 'model' => 'Ayla 1.0 X', 'type' => 'hatchback', 'year' => 2020,
                'color' => 'Silver', 'license_plate' => 'P 1039 UY', 'chassis_number' => 'MHKS1EJ2JLK012485', 'engine_number' => '1KRF0478216',
                'daily_rate' => 250000, 'photo' => null, 'status' => 'tidak_tersedia', 'odometer' => 168492, 'fuel_level' => 'quarter',
                'notes' => 'Unit dijual 12 Agustus 2026; disimpan sebagai arsip operasional.', 'is_active' => false,
                'created_at' => '2025-01-20 09:00:00', 'updated_at' => '2026-08-12 16:00:00', 'deleted_at' => null,
            ],
        ]);
    }
}

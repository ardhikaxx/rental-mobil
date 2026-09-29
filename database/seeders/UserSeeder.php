<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Tim internal Jaya Trans Rental Mobil (2 pemilik, 4 admin operasional,
     * 5 staf garasi — 2 di antaranya sudah nonaktif).
     *
     * Akun demo (password: "password"):
     *   superadmin  — Budi Santoso (Pemilik)
     *   adminoper   — Siti Rahma (Admin Operasional / Kasir)
     *   stafgarasi  — Andi Wijaya (Staf Garasi)
     */
    public function run(): void
    {
        User::insert([
            [
                'id' => 1, 'name' => 'Budi Santoso', 'username' => 'superadmin', 'email' => 'budi.santoso@jayatrans.test',
                'phone' => '081134767890', 'role' => 'super_admin', 'is_active' => true,
                'email_verified_at' => '2025-01-02 08:00:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-01-02 08:00:00', 'updated_at' => '2026-09-01 09:15:00',
            ],
            [
                'id' => 2, 'name' => 'Ratna Kusuma Wardani', 'username' => 'ratna.wardani', 'email' => 'ratna.wardani@jayatrans.test',
                'phone' => '081257840913', 'role' => 'super_admin', 'is_active' => true,
                'email_verified_at' => '2025-01-02 08:10:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-01-02 08:10:00', 'updated_at' => '2026-08-21 14:40:00',
            ],
            [
                'id' => 3, 'name' => 'Siti Rahma', 'username' => 'adminoper', 'email' => 'siti.rahma@jayatrans.test',
                'phone' => '082198764305', 'role' => 'admin', 'is_active' => true,
                'email_verified_at' => '2025-01-03 08:30:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-01-03 08:30:00', 'updated_at' => '2026-09-27 16:05:00',
            ],
            [
                'id' => 4, 'name' => 'Arif Budiman', 'username' => 'arif.budiman', 'email' => 'arif.budiman@jayatrans.test',
                'phone' => '085731624908', 'role' => 'admin', 'is_active' => true,
                'email_verified_at' => '2025-02-10 09:00:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-02-10 09:00:00', 'updated_at' => '2026-09-28 11:20:00',
            ],
            [
                'id' => 5, 'name' => 'Nabila Putri Ramadhani', 'username' => 'nabila.putri', 'email' => 'nabila.putri@jayatrans.test',
                'phone' => '081339672085', 'role' => 'admin', 'is_active' => true,
                'email_verified_at' => '2025-06-16 08:45:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-06-16 08:45:00', 'updated_at' => '2026-09-25 10:12:00',
            ],
            [
                'id' => 6, 'name' => 'Andi Wijaya', 'username' => 'stafgarasi', 'email' => 'andi.wijaya@jayatrans.test',
                'phone' => '082264590173', 'role' => 'staff', 'is_active' => true,
                'email_verified_at' => '2025-01-03 08:40:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-01-03 08:40:00', 'updated_at' => '2026-09-28 17:30:00',
            ],
            [
                'id' => 7, 'name' => 'Bagas Aditya Pratama', 'username' => 'bagas.aditya', 'email' => 'bagas.aditya@jayatrans.test',
                'phone' => '085855302119', 'role' => 'staff', 'is_active' => true,
                'email_verified_at' => '2025-03-03 08:15:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-03-03 08:15:00', 'updated_at' => '2026-09-29 07:55:00',
            ],
            [
                'id' => 8, 'name' => 'Raka Dwi Saputra', 'username' => 'raka.saputra', 'email' => 'raka.saputra@jayatrans.test',
                'phone' => '081947256830', 'role' => 'staff', 'is_active' => true,
                'email_verified_at' => '2025-08-04 09:20:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-08-04 09:20:00', 'updated_at' => '2026-09-26 15:45:00',
            ],
            [
                'id' => 9, 'name' => 'Fajar Maulana Iskandar', 'username' => 'fajar.maulana', 'email' => 'fajar.maulana@jayatrans.test',
                'phone' => '087714609325', 'role' => 'staff', 'is_active' => true,
                'email_verified_at' => '2026-01-12 08:05:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2026-01-12 08:05:00', 'updated_at' => '2026-09-27 08:40:00',
            ],
            [
                'id' => 10, 'name' => 'Hendra Purnama', 'username' => 'hendra.purnama', 'email' => 'hendra.purnama@jayatrans.test',
                'phone' => '081536847092', 'role' => 'admin', 'is_active' => false,
                'email_verified_at' => '2025-04-07 08:30:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-04-07 08:30:00', 'updated_at' => '2026-06-30 17:00:00',
            ],
            [
                'id' => 11, 'name' => 'Yusuf Ramadhan Hakim', 'username' => 'yusuf.ramadhan', 'email' => 'yusuf.ramadhan@jayatrans.test',
                'phone' => '085249073168', 'role' => 'staff', 'is_active' => false,
                'email_verified_at' => '2025-09-01 08:20:00', 'password' => bcrypt('password'),
                'remember_token' => null, 'created_at' => '2025-09-01 08:20:00', 'updated_at' => '2026-08-31 16:30:00',
            ],
        ]);
    }
}

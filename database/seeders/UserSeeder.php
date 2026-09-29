<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demo accounts — development only:
     *   superadmin / password  (Super Admin / Pemilik)
     *   adminoper  / password  (Admin Operasional / Kasir)
     *   stafgarasi / password  (Staf Garasi)
     */
    public function run(): void
    {
        User::query()->upsert(
            [
                ['name' => 'Budi Santoso', 'username' => 'superadmin', 'email' => 'budi@rental.test', 'phone' => '08111111111', 'role' => UserRole::SuperAdmin->value, 'is_active' => true, 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Siti Rahma', 'username' => 'adminoper', 'email' => 'siti@rental.test', 'phone' => '08222222222', 'role' => UserRole::Admin->value, 'is_active' => true, 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Andi Wijaya', 'username' => 'stafgarasi', 'email' => 'andi@rental.test', 'phone' => '08333333333', 'role' => UserRole::Staff->value, 'is_active' => true, 'password' => bcrypt('password'), 'created_at' => now(), 'updated_at' => now()],
            ],
            ['username'],
            ['name', 'email', 'phone', 'role', 'is_active', 'password', 'updated_at'],
        );
    }
}

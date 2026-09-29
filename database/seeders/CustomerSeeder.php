<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Rudi Hartono', 'id_number' => '3271050101800001', 'phone' => '081234560001', 'email' => 'rudi.hartono@mail.test', 'address' => 'Jl. Melati No. 12, Jakarta Selatan'],
            ['name' => 'Dewi Lestari', 'id_number' => '3271050202850002', 'phone' => '081234560002', 'email' => 'dewi.lestari@mail.test', 'address' => 'Jl. Anggrek No. 4, Depok'],
            ['name' => 'Agus Salim', 'id_number' => '3271050303790003', 'phone' => '081234560003', 'email' => null, 'address' => 'Jl. Kenanga No. 8, Bekasi'],
            ['name' => 'Nia Kurnia', 'id_number' => '3271050404900004', 'phone' => '081234560004', 'email' => 'nia.kurnia@mail.test', 'address' => 'Jl. Mawar No. 21, Tangerang'],
            ['name' => 'Hendra Gunawan', 'id_number' => '3271050505750005', 'phone' => '081234560005', 'email' => null, 'address' => 'Jl. Dahlia No. 3, Jakarta Barat'],
            ['name' => 'Putri Amelia', 'id_number' => '3271050606950006', 'phone' => '081234560006', 'email' => 'putri.amelia@mail.test', 'address' => 'Jl. Flamboyan No. 17, Jakarta Timur'],
            ['name' => 'Joko Prasetyo', 'id_number' => '3271050707700007', 'phone' => '081234560007', 'email' => null, 'address' => 'Jl. Cempaka No. 9, Bogor'],
            ['name' => 'Maya Sari', 'id_number' => '3271050808880008', 'phone' => '081234560008', 'email' => 'maya.sari@mail.test', 'address' => 'Jl. Tulip No. 5, Jakarta Selatan'],
        ];

        foreach ($customers as $customer) {
            Customer::query()->firstOrCreate(
                ['id_number' => $customer['id_number']],
                $customer,
            );
        }
    }
}

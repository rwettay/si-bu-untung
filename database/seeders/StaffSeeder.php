<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat data staff dengan role 'owner'
        Staff::create([
            'id_staff' => 'OWN001',  // ID yang di-generate manual
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),  // Hash password dengan bcrypt
            'role' => 'owner',
        ]);

        // Membuat data staff dengan role 'manager'
        Staff::create([
            'id_staff' => 'MGR001',
            'username' => 'manager1',
            'email' => 'manager1@example.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
        ]);

        // Membuat data staff dengan role 'karyawan'
        Staff::create([
            'id_staff' => 'KRY001',
            'username' => 'employee1',
            'email' => 'employee1@example.com',
            'password' => Hash::make('employee123'),
            'role' => 'karyawan',
        ]);
    }
}

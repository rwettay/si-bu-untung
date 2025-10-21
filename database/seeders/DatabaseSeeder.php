<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Panggil StaffSeeder untuk memasukkan data staff
        $this->call([
            StaffSeeder::class,
            PelangganSeeder::class,
            BarangSeeder::class,
        ]);
    }
}

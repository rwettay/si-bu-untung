<?php
// database/seeders/PelangganSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        // PLG001
        Pelanggan::updateOrCreate(
            ['id_pelanggan' => 'PLG001'],   // kunci unik
            [
                'nama_pelanggan' => 'Muhammad Ilham',
                'alamat'         => 'Jl. Contoh No. 1',
                'no_hp'          => '081234567890',
                'email'          => 'ilham@example.com',
                'username'       => 'ilham',
                'password'       => Hash::make('password'),
            ]
        );

        // PLG002
        Pelanggan::updateOrCreate(
            ['id_pelanggan' => 'PLG002'],
            [
                'nama_pelanggan' => 'Siti Aminah',
                'alamat'         => 'Jl. Mawar No. 2',
                'no_hp'          => '089876543210',
                'email'          => 'siti@example.com',
                'username'       => 'siti',
                'password'       => Hash::make('password'),
            ]
        );
    }
}

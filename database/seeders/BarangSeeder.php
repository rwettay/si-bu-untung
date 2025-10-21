<?php
// database/seeders/BarangSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // rekomendasi
            ['id'=>'rkk001','nama'=>'Esse Cigarette Light Blue Rokok 20 Batang', 'harga'=>43900, 'img'=>'https://c.alfagift.id/product/1/1_A13420006575_20241004171733414_base.jpg', 'rec'=>true],
            ['id'=>'rkk002','nama'=>'Marlboro Merah Rokok 20 Batang',            'harga'=>56400, 'img'=>'https://c.alfagift.id/product/1/1_A13420003316_20241004145407670_base.jpg', 'rec'=>true],
            ['id'=>'rkk003','nama'=>'Wismilak Diplomat Rokok 12 Batang',         'harga'=>27100, 'img'=>'https://c.alfagift.id/product/1/1_A13420003080_20241004100845721_base.jpg', 'rec'=>true],
            ['id'=>'rkk004','nama'=>'Dunhill Light Rokok 20 Batang',             'harga'=>31600, 'img'=>'https://c.alfagift.id/product/1/1_A13420004191_20241003132055552_base.jpg', 'rec'=>true],
            ['id'=>'rkk005','nama'=>'Clas Mild Rokok 16 Batang',                 'harga'=>32300, 'img'=>'https://c.alfagift.id/product/1/1_A13420001761_20241004162840620_base.jpg', 'rec'=>true],
            ['id'=>'rkk006','nama'=>'Aroma Slim Rokok 16 Batang',                'harga'=>15700, 'img'=>'https://c.alfagift.id/product/1/1_A6639780001022_20210510140428205_base.jpg', 'rec'=>true],

            // non-rekomendasi
            ['id'=>'rkk007','nama'=>'Win Bold Rokok 20 Batang',                  'harga'=>31000, 'img'=>'https://c.alfagift.id/product/1/1_A7630640001109_20241004101100265_base.jpg', 'rec'=>false],
            ['id'=>'rkk008','nama'=>'Sampoerna Prima Rokok 12 Batang',           'harga'=>15000, 'img'=>'https://c.alfagift.id/product/1/1_A8155770002167_20241003110353053_base.jpg', 'rec'=>false],
            ['id'=>'rkk009','nama'=>'Djarum Safari Rokok 12 Batang',             'harga'=>14000, 'img'=>'https://c.alfagift.id/product/1/1_A8248560002167_20250122163557690_base.jpg', 'rec'=>false],
            ['id'=>'rkk010','nama'=>'Juara Apel Rokok 12 Batang',                'harga'=>15000, 'img'=>'https://c.alfagift.id/product/1/1_A8277370002167_20250428155000077_base.jpg', 'rec'=>false],
            ['id'=>'rkk011','nama'=>'Aroma Bold Rokok 12 Batang',                'harga'=>18400, 'img'=>'https://c.alfagift.id/product/1/1_A7666220001104_202104121051410330_base.jpg', 'rec'=>false],
            ['id'=>'rkk012','nama'=>'Bentoel Sejati Rokok 12 Batang',            'harga'=>10000, 'img'=>'https://c.alfagift.id/product/1/1_A8179390002167_20241003103237093_base.jpg', 'rec'=>false],
        ];

        foreach ($items as $i) {
            Barang::updateOrCreate(
                ['id_barang' => $i['id']], // kunci unik
                [
                    'nama_barang'         => $i['nama'],
                    'stok_barang'         => 100,
                    'harga_satuan'        => $i['harga'],
                    'gambar_url'          => $i['img'],
                    'tanggal_kedaluwarsa' => null,
                    'is_recommended'      => $i['rec'],
                    'sold_count'          => rand(10, 60), // biar "Terlaris" tampil
                ]
            );
        }
    }
}

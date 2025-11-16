<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Lepas FK di tabel anak (transaksi)
        DB::statement("ALTER TABLE `transaksi` DROP FOREIGN KEY `transaksi_id_pelanggan_foreign`");

        // 2) Samakan tipe/panjang kolom di tabel anak dulu
        DB::statement("ALTER TABLE `transaksi` MODIFY `id_pelanggan` VARCHAR(12) NOT NULL");

        // 3) Baru ubah kolom di tabel induk
        DB::statement("ALTER TABLE `pelanggan` MODIFY `id_pelanggan` VARCHAR(12) NOT NULL");

        // 4) Pasang lagi FK-nya (silakan atur aksi ON UPDATE/DELETE sesuai kebutuhan)
        DB::statement("
            ALTER TABLE `transaksi`
            ADD CONSTRAINT `transaksi_id_pelanggan_foreign`
            FOREIGN KEY (`id_pelanggan`)
            REFERENCES `pelanggan`(`id_pelanggan`)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
        ");
    }

    public function down(): void
    {
        // Asumsikan panjang lama = VARCHAR(8). Ubah kalau beda.
        DB::statement("ALTER TABLE `transaksi` DROP FOREIGN KEY `transaksi_id_pelanggan_foreign`");
        DB::statement("ALTER TABLE `transaksi` MODIFY `id_pelanggan` VARCHAR(8) NOT NULL");
        DB::statement("ALTER TABLE `pelanggan` MODIFY `id_pelanggan` VARCHAR(8) NOT NULL");
        DB::statement("
            ALTER TABLE `transaksi`
            ADD CONSTRAINT `transaksi_id_pelanggan_foreign`
            FOREIGN KEY (`id_pelanggan`)
            REFERENCES `pelanggan`(`id_pelanggan`)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
        ");
    }
};

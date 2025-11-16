<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Pastikan kolom flags ada
        if (!Schema::hasColumn('barang','is_recommended')) {
            Schema::table('barang', function ($t) {
                $t->boolean('is_recommended')->default(false)->index();
            });
        }
        if (!Schema::hasColumn('barang','sold_count')) {
            Schema::table('barang', function ($t) {
                $t->unsignedInteger('sold_count')->default(0)->index();
            });
        }

        // 2) Bikin tanggal_kedaluwarsa nullable (jika perlu)
        // Sesuaikan tipe kolom di DB kamu: DATE/DATETIME/TIMESTAMP
        DB::statement('ALTER TABLE barang MODIFY tanggal_kedaluwarsa DATE NULL');

        // 3) Ubah id_barang jadi VARCHAR dan jadikan PRIMARY KEY non-increment
        // Lepas PK lama (kalau ada) lalu jadikan id_barang sebagai PK
        // (nama constraint bisa bervariasi; ini cara aman: drop PK lalu set lagi)
        DB::statement('ALTER TABLE barang DROP PRIMARY KEY');
        DB::statement('ALTER TABLE barang MODIFY id_barang VARCHAR(16) NOT NULL');
        DB::statement('ALTER TABLE barang ADD PRIMARY KEY (id_barang)');
    }

    public function down(): void
    {
        // Rollback best-effort (kembalikan ke INT tanpa AI)
        DB::statement('ALTER TABLE barang DROP PRIMARY KEY');
        DB::statement('ALTER TABLE barang MODIFY id_barang INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE barang ADD PRIMARY KEY (id_barang)');

        // (opsional) balikan nullable
        DB::statement('ALTER TABLE barang MODIFY tanggal_kedaluwarsa DATE NOT NULL');

        // (opsional) hapus flags
        Schema::table('barang', function ($t) {
            if (Schema::hasColumn('barang','is_recommended')) $t->dropColumn('is_recommended');
            if (Schema::hasColumn('barang','sold_count'))     $t->dropColumn('sold_count');
        });
    }
};

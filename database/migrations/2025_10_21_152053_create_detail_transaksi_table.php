<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->bigIncrements('id_detail');

            // FK ke transaksi (string 20)
            $table->string('id_transaksi', 20);
            $table->string('id_barang', 20);

            // qty & subtotal per item
            $table->integer('jumlah_pesanan');
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();

            // Index untuk performa
            $table->index(['id_transaksi']);
            $table->index(['id_barang']);

            // Cegah duplikasi baris yang sama (transaksi + barang)
            $table->unique(['id_transaksi', 'id_barang'], 'uniq_transaksi_barang');

            // Relasi & cascade delete detail jika header transaksi dihapus
            $table->foreign('id_transaksi')
                  ->references('id_transaksi')->on('transaksi')
                  ->onDelete('cascade');

            // Jika tabel 'barang' ada dan PK-nya 'id_barang', aktifkan FK ini:
            // $table->foreign('id_barang')
            //       ->references('id_barang')->on('barang')
            //       ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('detail_transaksi', function (Blueprint $t) {
      $t->string('id_transaksi', 20);
      $t->string('id_barang', 20);
      $t->integer('jumlah_pesanan');             // validasi >0 di app
      $t->decimal('subtotal', 10, 2);            // auto via trigger
      $t->timestamps();

      $t->primary(['id_transaksi','id_barang']);
      $t->index('id_barang', 'fk_detail_barang');

      $t->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->cascadeOnDelete();
      $t->foreign('id_barang')->references('id_barang')->on('barang');
    });
  }
  public function down(): void {
    Schema::dropIfExists('detail_transaksi');
  }
};

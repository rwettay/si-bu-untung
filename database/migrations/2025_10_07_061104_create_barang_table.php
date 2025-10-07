<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('barang', function (Blueprint $t) {
    $t->string('id_barang', 20)->primary();
    $t->text('nama_barang');
    $t->integer('stok_barang');                 // validasi >=0 di app
    $t->decimal('harga_satuan', 10, 2);         // validasi >=0 di app
    $t->text('gambar_url')->nullable();         // <— link gambar (panjang aman)
    $t->date('tanggal_kedaluwarsa')->nullable();
    $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('barang');
  }
};

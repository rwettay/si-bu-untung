<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
Schema::create('barang', function (Blueprint $t) {
    $t->string('id_barang', 16)->primary();
    $t->string('nama_barang');
    $t->unsignedInteger('stok_barang')->default(0);
    $t->unsignedInteger('harga_satuan')->default(0); // simpan rupiah sebagai integer
    $t->date('tanggal_kedaluwarsa')->nullable();
    $t->string('gambar_url')->nullable();            // atau 'gambar' jika kamu mau konsisten ke situ
    $t->boolean('is_recommended')->default(false);
    $t->unsignedInteger('sold_count')->default(0);
    $t->timestamps();
});
  }
  public function down(): void {
    Schema::dropIfExists('barang');
  }
};

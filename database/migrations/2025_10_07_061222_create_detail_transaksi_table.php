<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
Schema::create('detail_transaksi', function (Blueprint $t) {
    $t->id();
    $t->string('id_transaksi', 20);
    $t->string('id_barang', 16); // harus STRING juga
    $t->unsignedInteger('qty');
    $t->unsignedInteger('harga_satuan');
    $t->timestamps();

    $t->foreign('id_barang')->references('id_barang')->on('barang')
      ->cascadeOnUpdate()->restrictOnDelete();
});

  }
  public function down(): void {
    Schema::dropIfExists('detail_transaksi');
  }
};

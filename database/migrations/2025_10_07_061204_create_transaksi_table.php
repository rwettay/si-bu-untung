<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('transaksi', function (Blueprint $t) {
      $t->string('id_transaksi', 20)->primary();
      $t->string('id_pelanggan', 20);
      $t->decimal('total_transaksi', 10, 2)->default(0.00); // dihitung via trigger
      $t->date('tanggal_transaksi');
      $t->string('id_staff', 20)->nullable();
      $t->enum('status_transaksi', ['pending','dibayar','dikirim']);
      $t->timestamps();

      $t->index('id_pelanggan', 'fk_transaksi_pelanggan');
      $t->index('id_staff', 'fk_transaksi_staff');

      $t->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan');
      $t->foreign('id_staff')->references('id_staff')->on('staff')->nullOnDelete();
    });
  }
  public function down(): void {
    Schema::dropIfExists('transaksi');
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('pelanggan', function (Blueprint $t) {
      $t->string('id_pelanggan', 20)->primary();
      $t->string('nama_pelanggan', 100);
      $t->text('alamat')->nullable();
      $t->string('no_hp', 15)->nullable();
      $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('pelanggan');
  }
};

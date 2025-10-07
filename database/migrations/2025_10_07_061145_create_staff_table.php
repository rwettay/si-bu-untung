<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('staff', function (Blueprint $t) {
      $t->string('id_staff', 20)->primary();
      $t->string('username', 50)->unique();
      $t->string('password', 255);
      $t->enum('role', ['owner','manager','karyawan']);
      $t->timestamps();
    });
  }
  public function down(): void {
    Schema::dropIfExists('staff');
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('pelanggan', function (Blueprint $t) {
      if (!Schema::hasColumn('pelanggan', 'username')) {
        $t->string('username', 50)->unique()->after('no_hp');
      }
      // pastikan kolom email ada (opsional kalau sudah)
      if (!Schema::hasColumn('pelanggan', 'email')) {
        $t->string('email', 100)->unique()->after('username');
      }
    });
  }
  public function down(): void {
    Schema::table('pelanggan', function (Blueprint $t) {
      if (Schema::hasColumn('pelanggan', 'email')) $t->dropColumn('email');
      if (Schema::hasColumn('pelanggan', 'username')) $t->dropColumn('username');
    });
  }
};

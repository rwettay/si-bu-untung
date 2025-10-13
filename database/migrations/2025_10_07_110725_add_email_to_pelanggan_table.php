<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
  public function up(): void {
    Schema::table('pelanggan', function (Blueprint $t) {
      $t->string('email', 100)->unique()->after('no_hp');
    });
  }
  public function down(): void {
    Schema::table('pelanggan', function (Blueprint $t) {
      $t->dropColumn('email');
    });
  }
};

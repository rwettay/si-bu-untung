<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
    Schema::table('pelanggan', function (Blueprint $t) {
        $t->string('username', 50)->unique()->after('no_hp');
        $t->string('password', 255)->after('username');
    });
}

public function down(): void {
    Schema::table('pelanggan', function (Blueprint $t) {
        $t->dropColumn(['username','password']);
    });
}
};

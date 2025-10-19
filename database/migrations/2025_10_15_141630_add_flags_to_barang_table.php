<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::table('barang', function (Blueprint $t) {
            $t->boolean('is_recommended')->default(false)->index();
            $t->unsignedInteger('sold_count')->default(0)->index();
        });
    }
    public function down(){
        Schema::table('barang', function (Blueprint $t) {
            $t->dropColumn(['is_recommended','sold_count']);
        });
    }
};

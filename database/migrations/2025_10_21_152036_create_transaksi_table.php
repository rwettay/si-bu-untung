<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            // PK string (varchar 20)
            $table->string('id_transaksi', 20)->primary();

            // FK ke pelanggan & staff (opsional ->nullable()).
            // Sesuaikan nama tabel referensinya kalau kamu ingin pakai foreign key sungguhan.
            $table->string('id_pelanggan', 20)->nullable()->index();
            $table->string('id_staff', 20)->nullable()->index();

            $table->decimal('total_transaksi', 10, 2)->default(0);
            $table->date('tanggal_transaksi');

            // Enum status
            $table->enum('status_transaksi', ['pending', 'dibayar', 'dikirim'])
                  ->default('pending');

            $table->timestamps();

            // Jika tabel pelanggan/staff sudah ada dan ingin FK beneran, buka komentar di bawah:
            // $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->nullOnDelete();
            // $table->foreign('id_staff')->references('id_staff')->on('staff')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::dropIfExists('penyewaans'); // Drop the table if it exists
    Schema::create('penyewaans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pelanggan_id')->constrained();
        $table->foreignId('kendaraan_id')->constrained();
        $table->dateTime('tanggal_mulai');
        $table->dateTime('tanggal_selesai');
        $table->decimal('total_biaya', 10, 2);
        $table->enum('status', ['reservasi', 'berjalan', 'selesai', 'batal'])->default('reservasi');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaans');
    }
};

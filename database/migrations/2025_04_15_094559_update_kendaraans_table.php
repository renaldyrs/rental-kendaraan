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
        //
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->string('warna')->after('model');
            $table->integer('tahun')->after('warna');
            $table->string('bahan_bakar')->after('tahun');
            $table->integer('kapasitas_penumpang')->after('bahan_bakar');
            $table->enum('transmisi', ['manual', 'automatic'])->after('kapasitas_penumpang');
            $table->text('fasilitas')->nullable()->after('deskripsi');
            $table->enum('kategori', ['mobil', 'motor', 'truk', 'bus'])->after('transmisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

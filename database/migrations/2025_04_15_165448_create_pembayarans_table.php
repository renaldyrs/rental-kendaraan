<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained()->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->string('metode')->default('transfer');
            $table->string('bukti_pembayaran')->nullable();
            $table->dateTime('tanggal_bayar');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};

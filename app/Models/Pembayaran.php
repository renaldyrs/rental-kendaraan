<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'jumlah',
        'metode',
        'bukti_pembayaran',
        'tanggal_bayar',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah' => 'float'
    ];

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }
}
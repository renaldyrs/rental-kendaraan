<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Penyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'kendaraan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_biaya',
        'status',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'dibayar',
        'catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'total_biaya' => 'float',
        'dibayar' => 'float'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function getDurasiAttribute()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'reservasi' => ['label' => 'Reservasi', 'color' => 'blue'],
            'berjalan' => ['label' => 'Berjalan', 'color' => 'yellow'],
            'selesai' => ['label' => 'Selesai', 'color' => 'green'],
            'batal' => ['label' => 'Batal', 'color' => 'red']
        ];

        return $statuses[$this->status] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    public function getPembayaranLabelAttribute()
    {
        $statuses = [
            'pending' => ['label' => 'Belum Lunas', 'color' => 'red'],
            'lunas' => ['label' => 'Lunas', 'color' => 'green'],
            'sebagian' => ['label' => 'DP', 'color' => 'yellow']
        ];

        return $statuses[$this->status_pembayaran] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    public function updateStatusPembayaran($jumlah)
    {
        $this->dibayar += $jumlah;
        
        if ($this->dibayar >= $this->total_biaya) {
            $this->status_pembayaran = 'lunas';
        } elseif ($this->dibayar > 0) {
            $this->status_pembayaran = 'sebagian';
        } else {
            $this->status_pembayaran = 'pending';
        }
        
        $this->save();
    }
    public function payments()
    {
        return $this->hasMany(Pembayaran::class, 'penyewaan_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'merk',
        'model',
        'warna',
        'tahun',
        'nomor_plat',
        'bahan_bakar',
        'kapasitas_penumpang',
        'transmisi',
        'kategori',
        'tarif_sewa',
        'status',
        'gambar',
        'deskripsi',
        'fasilitas'
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'tahun' => 'integer',
        'kapasitas_penumpang' => 'integer',
        'tarif_sewa' => 'float'
    ];

    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'tersedia' => ['label' => 'Tersedia', 'color' => 'green'],
            'disewa' => ['label' => 'Disewa', 'color' => 'yellow'],
            'perbaikan' => ['label' => 'Perbaikan', 'color' => 'red']
        ];

        return $statuses[$this->status] ?? ['label' => 'Unknown', 'color' => 'gray'];
    }

    public function getTransmisiLabelAttribute()
    {
        return $this->transmisi === 'automatic' ? 'Automatic' : 'Manual';
    }

    public function getKategoriLabelAttribute()
    {
        return ucfirst($this->kategori);
    }

    public function scopeAvailable($query, $startDate, $endDate)
    {
        return $query->whereDoesntHave('penyewaans', function($q) use ($startDate, $endDate) {
            $q->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                      ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('tanggal_mulai', '<', $startDate)
                            ->where('tanggal_selesai', '>', $endDate);
                      });
            })
            ->whereIn('status', ['reservasi', 'berjalan']);
        })
        ->where('status', 'tersedia');
    }
}
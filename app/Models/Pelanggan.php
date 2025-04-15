<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'nama',
        'alamat',
        'no_telp',
        'email',
        'no_ktp',
        'foto_ktp',
        'tanggal_lahir',
        'jenis_kelamin',
        'catatan',
        'is_verified'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_verified' => 'boolean'
    ];

    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class);
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? now()->diffInYears($this->tanggal_lahir) : null;
    }

    public function getStatusVerifikasiAttribute()
    {
        return $this->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi';
    }

    public function verifikasi()
    {
        $this->update(['is_verified' => true]);
    }
}

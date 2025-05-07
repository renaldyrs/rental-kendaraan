<?php

namespace App\Services;

use App\Models\Kendaraan;
use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalService
{
    public function checkAvailability($kendaraan_id, $tanggal_mulai, $tanggal_selesai, $exclude_penyewaan_id = null)
    {
        $query = Penyewaan::where('kendaraan_id', $kendaraan_id)
            ->where(function($q) use ($tanggal_mulai, $tanggal_selesai) {
                $q->whereBetween('tanggal_mulai', [$tanggal_mulai, $tanggal_selesai])
                  ->orWhereBetween('tanggal_selesai', [$tanggal_mulai, $tanggal_selesai])
                  ->orWhere(function($q) use ($tanggal_mulai, $tanggal_selesai) {
                      $q->where('tanggal_mulai', '<', $tanggal_mulai)
                        ->where('tanggal_selesai', '>', $tanggal_selesai);
                  });
            })
            ->whereIn('status', ['reservasi', 'berjalan']);
            
        if ($exclude_penyewaan_id) {
            $query->where('id', '!=', $exclude_penyewaan_id);
        }
            
        return !$query->exists();
    }

    public function createRental($data)
{
    return DB::transaction(function () use ($data) {
        $kendaraan = Kendaraan::findOrFail($data['kendaraan_id']);
        
        // Pastikan tanggal valid
        $start = Carbon::parse($data['tanggal_mulai']);
        $end = Carbon::parse($data['tanggal_selesai']);
        
        // Validasi tanggal
        if ($end <= $start) {
            throw new \Exception("Tanggal selesai harus setelah tanggal mulai");
        }
        
        $days = $start->diffInDays($end); // +1 untuk menghitung inklusif
        
        // Pastikan tarif sewa valid
        if ($kendaraan->tarif_sewa <= 0) {
            throw new \Exception("Tarif sewa kendaraan tidak valid");
        }
        
        $totalBiaya = $days * $kendaraan->tarif_sewa;
        
        $penyewaan = Penyewaan::create([
            'pelanggan_id' => $data['pelanggan_id'],
            'kendaraan_id' => $data['kendaraan_id'],
            'tanggal_mulai' => $start,
            'tanggal_selesai' => $end,
            'total_biaya' => $totalBiaya,
            'status' => 'reservasi',
            'metode_pembayaran' => $data['metode_pembayaran'],
            'catatan' => $data['catatan'] ?? null
        ]);
        
        // Debug log
        \Log::info('Penyewaan created', [
            'days' => $days,
            'tarif' => $kendaraan->tarif_sewa,
            'total' => $totalBiaya
        ]);
        
        return $penyewaan;
    });
}

    public function getAvailableVehicles($startDate, $endDate, $kategori = null)
    {
        $query = Kendaraan::whereDoesntHave('penyewaans', function($q) use ($startDate, $endDate) {
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
        
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        
        return $query->get();
    }
}
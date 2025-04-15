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
            $start = Carbon::parse($data['tanggal_mulai']);
            $end = Carbon::parse($data['tanggal_selesai']);
            $days = $end->diffInDays($start) + 1;
            
            $penyewaan = Penyewaan::create([
                'pelanggan_id' => $data['pelanggan_id'],
                'kendaraan_id' => $data['kendaraan_id'],
                'tanggal_mulai' => $start,
                'tanggal_selesai' => $end,
                'total_biaya' => $days * $kendaraan->tarif_sewa,
                'status' => 'reservasi',
                'metode_pembayaran' => $data['metode_pembayaran'],
                'catatan' => $data['catatan'] ?? null
            ]);
            
            // Jika langsung disewa, update status
            if ($start->isToday()) {
                $kendaraan->update(['status' => 'disewa']);
                $penyewaan->update(['status' => 'berjalan']);
            }
            
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
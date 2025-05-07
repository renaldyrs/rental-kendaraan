<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Penyewaan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Count data
        $totalKendaraan = Kendaraan::count();
        $totalPelanggan = Pelanggan::count();
        $totalPenyewaanAktif = Penyewaan::where('status', 'berjalan')->count();

        // Current month revenue
        $pendapatanBulanIni = Penyewaan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'selesai')
            ->sum('total_biaya');

        // Previous month revenue for comparison
        $pendapatanBulanLalu = Penyewaan::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->where('status', 'selesai')
            ->sum('total_biaya');

        // Calculate percentage change
        $persentasePendapatan = $this->calculatePercentageChange($pendapatanBulanLalu, $pendapatanBulanIni);

        // Available vehicles
        $kendaraanTersedia = Kendaraan::where('status', 'tersedia')->count();

        // New customers this month
        $pelangganBaruBulanIni = Pelanggan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Recent rentals
        $penyewaanTerbaru = Penyewaan::with(['pelanggan', 'kendaraan'])
            ->latest()
            ->take(5)
            ->get();

        // Popular vehicles
        $kendaraanPopuler = Kendaraan::withCount(['penyewaans' => function($query) {
                $query->where('status', 'selesai');
            }])
            ->orderBy('penyewaans_count', 'desc')
            ->take(5)
            ->get();

        // Revenue data for chart (last 6 months)
        $chartData = $this->getRevenueData(6);
        $chartLabels = $chartData['labels'];
        $chartValues = $chartData['values'];

        // Rentals ending soon (optional)
        $penyewaanAkanBerakhir = Penyewaan::where('status', 'berjalan')
            ->where('tanggal_selesai', '<=', Carbon::now()->addDays(3))
            ->count();

        return view('dashboard.index', compact(
            'totalKendaraan',
            'totalPelanggan',
            'totalPenyewaanAktif',
            'pendapatanBulanIni',
            'pendapatanBulanLalu',
            'persentasePendapatan',
            'kendaraanTersedia',
            'pelangganBaruBulanIni',
            'penyewaanTerbaru',
            'kendaraanPopuler',
            'chartLabels',
            'chartValues',
            'chartData',
            'penyewaanAkanBerakhir'
        ));
    }

    /**
     * Calculate percentage change between two values
     */
    protected function calculatePercentageChange($oldValue, $newValue): float
    {
        if ($oldValue == 0) {
            return $newValue == 0 ? 0 : 100;
        }

        return round((($newValue - $oldValue) / $oldValue) * 100, 2);
    }

    /**
     * Get revenue data for the last X months
     */
    protected function getRevenueData(int $months): array
    {
        $endDate = Carbon::now()->endOfMonth();
        $startDate = Carbon::now()->subMonths($months - 1)->startOfMonth();

        $data = Penyewaan::selectRaw('
                EXTRACT(YEAR FROM created_at) AS year, 
                EXTRACT(MONTH FROM created_at) AS month, 
                SUM(total_biaya) AS total')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Fill in missing months with 0
        $results = [
            'labels' => [],
            'values' => []
        ];

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $found = false;
            foreach ($data as $item) {
                if ($item->year == $currentDate->year && $item->month == $currentDate->month) {
                    $results['labels'][] = $currentDate->format('M Y');
                    $results['values'][] = $item->total;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $results['labels'][] = $currentDate->format('M Y');
                $results['values'][] = 0;
            }

            $currentDate->addMonth();
        }

        return $results;
    }
}

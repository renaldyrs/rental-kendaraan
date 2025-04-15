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
        $totalKendaraan = Kendaraan::count();
        $totalPelanggan = Pelanggan::count();
        $totalPenyewaanAktif = Penyewaan::where('status', 'berjalan')->count();

        $pendapatanBulanIni = Penyewaan::whereMonth('created_at', Carbon::now()->month)
            ->where('status', 'selesai')
            ->sum('total_biaya');

        $kendaraanTersedia = Kendaraan::where('status', 'tersedia')->count();

        $penyewaanTerbaru = Penyewaan::with(['pelanggan', 'kendaraan'])
            ->latest()
            ->take(5)
            ->get();

        $kendaraanPopuler = Kendaraan::withCount('penyewaans')
            ->orderBy('penyewaans_count', 'desc')
            ->take(5)
            ->get();

        $revenueData = Penyewaan::selectRaw('YEAR(created_at) year, MONTH(created_at) month, SUM(total_biaya) total')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $revenueLabels = [];
        $revenueValues = [];

        foreach ($revenueData as $data) {
            $date = Carbon::create($data->year, $data->month, 1);
            $revenueLabels[] = $date->format('M Y');
            $revenueValues[] = $data->total;
        }

        return view('dashboard.index', compact(
            'totalKendaraan',
            'totalPelanggan',
            'totalPenyewaanAktif',
            'pendapatanBulanIni',
            'kendaraanTersedia',
            'penyewaanTerbaru',
            'kendaraanPopuler',
            'revenueLabels',
    'revenueValues'
    
        ));
    }
}
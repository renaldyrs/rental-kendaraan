<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        $revenueData = DB::table('penyewaans')
        ->selectRaw('EXTRACT(YEAR FROM created_at) AS year, EXTRACT(MONTH FROM created_at) AS month, SUM(total_biaya) AS total')
        ->where('status', 'selesai')
        ->whereBetween('created_at', ['2024-11-01 00:00:00', '2025-04-30 23:59:59'])
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
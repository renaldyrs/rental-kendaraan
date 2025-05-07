<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        $kategori = $request->input('kategori', 'all');

        $query = Penyewaan::with(['pelanggan', 'kendaraan'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($kategori !== 'all') {
            $query->whereHas('kendaraan', function($q) use ($kategori) {
                $q->where('kategori', $kategori);
            });
        }

        $penyewaans = $query->orderBy('created_at', 'desc')->paginate(20);
        $totalPendapatan = $query->sum('total_biaya');

        return view('laporan.index', compact(
            'penyewaans',
            'startDate',
            'endDate',
            'status',
            'kategori',
            'totalPendapatan'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        $kategori = $request->input('kategori', 'all');

        $fileName = 'laporan-penyewaan-' . $startDate . '_' . $endDate . '.xlsx';

        return Excel::download(new LaporanExport($startDate, $endDate, $status, $kategori), $fileName);
    }
}
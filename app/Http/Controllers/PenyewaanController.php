<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Http\Requests\StorePenyewaanRequest;
use App\Http\Requests\UpdatePenyewaanRequest;
use App\Services\RentalService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyewaanController extends Controller
{
    protected $rentalService;

    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    public function index(Request $request)
    {
        $query = Penyewaan::with(['pelanggan', 'kendaraan'])
            ->latest();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('pembayaran')) {
            $query->where('status_pembayaran', $request->pembayaran);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%")
                        ->orWhere('no_ktp', 'like', "%$search%");
                })
                    ->orWhereHas('kendaraan', function ($q) use ($search) {
                        $q->where('merk', 'like', "%$search%")
                            ->orWhere('model', 'like', "%$search%")
                            ->orWhere('nomor_plat', 'like', "%$search%");
                    });
            });
        }

        $penyewaans = $query->paginate(15);

        return view('penyewaan.index', compact('penyewaans'));
    }

    public function create(Request $request)
    {
        $kendaraan = null;
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');

        if ($request->has('kendaraan_id')) {
            $kendaraan = Kendaraan::find($request->kendaraan_id);
        }

        $pelanggans = Pelanggan::where('is_verified', true)->get();
        $kendaraans = $this->rentalService->getAvailableVehicles(
            $tanggal_mulai ?? now()->format('Y-m-d'),
            $tanggal_selesai ?? now()->addDays(1)->format('Y-m-d')
        );

        return view('penyewaan.create', compact('pelanggans', 'kendaraans', 'kendaraan', 'tanggal_mulai', 'tanggal_selesai'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'metode_pembayaran' => 'required|string',
            'catatan' => 'nullable|string',
            'jumlah_pembayaran' => 'nullable|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Validasi ketersediaan kendaraan
        if (
            !$this->rentalService->checkAvailability(
                $validated['kendaraan_id'],
                $validated['tanggal_mulai'],
                $validated['tanggal_selesai']
            )
        ) {
            return back()->with('error', 'Kendaraan tidak tersedia pada tanggal yang diminta')->withInput();
        }

        try {
            // Buat penyewaan baru
            $penyewaan = $this->rentalService->createRental([
                'pelanggan_id' => $validated['pelanggan_id'],
                'kendaraan_id' => $validated['kendaraan_id'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Handle pembayaran awal jika ada
            if (!empty($validated['jumlah_pembayaran']) && $validated['jumlah_pembayaran'] > 0) {
                $penyewaan->updateStatusPembayaran($validated['jumlah_pembayaran']);

                if ($request->hasFile('bukti_pembayaran')) {
                    $penyewaan->update([
                        'bukti_pembayaran' => $request->file('bukti_pembayaran')->store('pembayaran', 'public'),
                    ]);
                }
            }

            return redirect()->route('penyewaan.show', $penyewaan)
                ->with('success', 'Penyewaan berhasil dibuat');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Penyewaan $penyewaan)
    {
        $penyewaan->pembayarans = $penyewaan->pembayarans ?? collect();
        $penyewaan->load(['payments', 'pelanggan', 'kendaraan']);
        return view('penyewaan.show', compact('penyewaan'));
    }

    public function edit(Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'reservasi') {
            return back()->with('error', 'Hanya penyewaan dengan status reservasi yang bisa diubah');
        }

        $pelanggans = Pelanggan::where('is_verified', true)->get();
        $kendaraans = Kendaraan::where('status', 'tersedia')
            ->orWhere('id', $penyewaan->kendaraan_id)
            ->get();

        return view('penyewaan.edit', compact('penyewaan', 'pelanggans', 'kendaraans'));
    }


    public function update(Request $request, Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'reservasi') {
            return back()->with('error', 'Hanya penyewaan dengan status reservasi yang bisa diubah');
        }

        // Validasi input
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'metode_pembayaran' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        // Validasi ketersediaan kendaraan jika berubah
        if (
            $penyewaan->kendaraan_id != $validated['kendaraan_id'] ||
            $penyewaan->tanggal_mulai != $validated['tanggal_mulai'] ||
            $penyewaan->tanggal_selesai != $validated['tanggal_selesai']
        ) {

            if (
                !$this->rentalService->checkAvailability(
                    $validated['kendaraan_id'],
                    $validated['tanggal_mulai'],
                    $validated['tanggal_selesai'],
                    $penyewaan->id // exclude current rental
                )
            ) {
                return back()->with('error', 'Kendaraan tidak tersedia pada tanggal yang diminta')->withInput();
            }

            // Hitung ulang total biaya
            $kendaraan = Kendaraan::find($validated['kendaraan_id']);
            $start = Carbon::parse($validated['tanggal_mulai']);
            $end = Carbon::parse($validated['tanggal_selesai']);
            $days = $end->diffInDays($start) + 1;

            $validated['total_biaya'] = $days * $kendaraan->tarif_sewa;
        }

        $penyewaan->update($validated);

        return redirect()->route('penyewaan.show', $penyewaan)
            ->with('success', 'Penyewaan berhasil diperbarui');
    }


    public function destroy(Penyewaan $penyewaan)
    {
        if ($penyewaan->status === 'berjalan') {
            return back()->with('error', 'Tidak dapat menghapus penyewaan yang sedang berjalan');
        }

        $penyewaan->delete();

        return redirect()->route('penyewaan.index')
            ->with('success', 'Penyewaan berhasil dihapus');
    }

    public function start(Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'reservasi') {
            return back()->with('error', 'Hanya penyewaan dengan status reservasi yang bisa dimulai');
        }

        DB::transaction(function () use ($penyewaan) {
            $penyewaan->update([
                'status' => 'berjalan',
                'tanggal_mulai' => now(),
            ]);

            $penyewaan->kendaraan->update(['status' => 'disewa']);
        });

        return back()->with('success', 'Penyewaan telah dimulai');
    }

    public function complete(Penyewaan $penyewaan)
    {
        if ($penyewaan->status !== 'berjalan') {
            return back()->with('error', 'Hanya penyewaan yang sedang berjalan yang bisa diselesaikan');
        }

        DB::transaction(function () use ($penyewaan) {
            $penyewaan->update([
                'status' => 'selesai',
                'tanggal_selesai' => now(),
            ]);

            $penyewaan->kendaraan->update(['status' => 'tersedia']);
        });

        return back()->with('success', 'Penyewaan telah diselesaikan');
    }
    public function cancel(Penyewaan $penyewaan)
    {
        if (!in_array($penyewaan->status, ['reservasi', 'berjalan'])) {
            return back()->with('error', 'Hanya penyewaan dengan status reservasi atau berjalan yang bisa dibatalkan');
        }

        DB::transaction(function () use ($penyewaan) {
            $statusSebelumnya = $penyewaan->status;
            $penyewaan->update(['status' => 'batal']);

            if ($statusSebelumnya === 'berjalan') {
                $penyewaan->kendaraan->update(['status' => 'tersedia']);
            }
        });

        return back()->with('success', 'Penyewaan telah dibatalkan');
    }


    public function payment(Request $request, Penyewaan $penyewaan)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($validated, $penyewaan) {
            $penyewaan->updateStatusPembayaran($validated['jumlah']);

            if (!empty($validated['bukti_pembayaran'])) {
                // Hapus bukti lama jika ada
                if ($penyewaan->bukti_pembayaran) {
                    Storage::disk('public')->delete($penyewaan->bukti_pembayaran);
                }

                $penyewaan->update([
                    'bukti_pembayaran' => $validated['bukti_pembayaran']->store('pembayaran', 'public'),
                ]);
            }
        });

        return back()->with('success', 'Pembayaran berhasil dicatat');
    }
}
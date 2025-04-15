<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;
use App\Http\Requests\StoreKendaraanRequest;
use App\Http\Requests\UpdateKendaraanRequest;
use Illuminate\Support\Facades\Storage;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kendaraan::query();
        
        // Search logic
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merk', 'like', "%$search%")
                  ->orWhere('model', 'like', "%$search%")
                  ->orWhere('nomor_plat', 'like', "%$search%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && in_array($request->status, ['tersedia', 'disewa', 'perbaikan'])) {
            $query->where('status', $request->status);
        }
        
        // Filter by kategori
        if ($request->has('kategori') && in_array($request->kategori, ['mobil', 'motor', 'truk', 'bus'])) {
            $query->where('kategori', $request->kategori);
        }
        
        $kendaraans = $query->latest()->paginate(12);
        
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        return view('kendaraan.create');
    }

    public function store(StoreKendaraanRequest $request)
    {
        $data = $request->validated();
        
        // Handle fasilitas
        if ($request->has('fasilitas')) {
            $data['fasilitas'] = explode(',', $request->fasilitas);
        }
        
        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('kendaraan', 'public');
        }
        
        Kendaraan::create($data);
        
        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function show(Kendaraan $kendaraan)
    {
        $penyewaans = $kendaraan->penyewaans()
            ->with('pelanggan')
            ->latest()
            ->paginate(5);
            
        return view('kendaraan.show', compact('kendaraan', 'penyewaans'));
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('kendaraan.edit', compact('kendaraan'));
    }

    public function update(UpdateKendaraanRequest $request, Kendaraan $kendaraan)
    {
        $data = $request->validated();
        
        // Handle fasilitas
        if ($request->has('fasilitas')) {
            $data['fasilitas'] = explode(',', $request->fasilitas);
        }
        
        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($kendaraan->gambar) {
                Storage::disk('public')->delete($kendaraan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('kendaraan', 'public');
        }
        
        $kendaraan->update($data);
        
        return redirect()->route('kendaraan.show', $kendaraan)
            ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        // Prevent deletion if has active rentals
        if ($kendaraan->penyewaans()->whereIn('status', ['reservasi', 'berjalan'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus kendaraan dengan penyewaan aktif');
        }
        
        // Delete image if exists
        if ($kendaraan->gambar) {
            Storage::disk('public')->delete($kendaraan->gambar);
        }
        
        $kendaraan->delete();
        
        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus');
    }

    public function updateStatus(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'status' => 'required|in:tersedia,disewa,perbaikan'
        ]);
        
        $kendaraan->update(['status' => $request->status]);
        
        return back()->with('success', 'Status kendaraan berhasil diperbarui');
    }

    
}
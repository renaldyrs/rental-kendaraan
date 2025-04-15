<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Requests\StorePelangganRequest;
use App\Http\Requests\UpdatePelangganRequest;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();
        
        // Search logic
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('no_ktp', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('no_telp', 'like', "%$search%");
            });
        }
        
        // Filter verification status
        if ($request->has('verifikasi') && in_array($request->verifikasi, ['1', '0'])) {
            $query->where('is_verified', $request->verifikasi);
        }
        
        $pelanggans = $query->latest()->paginate(10);
        
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        
        return view('pelanggan.create');
    }

    public function store(StorePelangganRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('foto_ktp')) {
            $data['foto_ktp'] = $request->file('foto_ktp')->store('pelanggan/ktp', 'public');
        }
        
        Pelanggan::create($data);
        
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(Pelanggan $pelanggan)
    {
        $penyewaans = $pelanggan->penyewaans()->with('kendaraan')->latest()->paginate(5);
        
        return view('pelanggan.show', compact('pelanggan', 'penyewaans'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(UpdatePelangganRequest $request, Pelanggan $pelanggan)
    {
        $data = $request->validated();
        
        if ($request->hasFile('foto_ktp')) {
            // Delete old photo if exists
            if ($pelanggan->foto_ktp) {
                Storage::disk('public')->delete($pelanggan->foto_ktp);
            }
            $data['foto_ktp'] = $request->file('foto_ktp')->store('pelanggan/ktp', 'public');
        }
        
        $pelanggan->update($data);
        
        return redirect()->route('pelanggan.show', $pelanggan)
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // Prevent deletion if has active rentals
        if ($pelanggan->penyewaans()->whereIn('status', ['reservasi', 'berjalan'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus pelanggan dengan penyewaan aktif');
        }
        
        // Delete KTP photo if exists
        if ($pelanggan->foto_ktp) {
            Storage::disk('public')->delete($pelanggan->foto_ktp);
        }
        
        $pelanggan->delete();
        
        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }

    public function verifikasi(Pelanggan $pelanggan)
    {
        $pelanggan->verifikasi();
        
        return back()->with('success', 'Pelanggan berhasil diverifikasi');
    }
}
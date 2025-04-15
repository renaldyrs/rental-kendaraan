@extends('layouts.dashboard')



@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-6">Tambah Kendaraan</h2>
    
    <form method="POST" action="{{ route('kendaraan.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($method ?? false)
            @method($method)
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-4">
                <div>
                    <label for="merk" class="block text-sm font-medium text-gray-700">Merk*</label>
                    <input type="text" id="merk" name="merk" value="{{ old('merk', $kendaraan->merk ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('merk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">Model*</label>
                    <input type="text" id="model" name="model" value="{{ old('model', $kendaraan->model ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="warna" class="block text-sm font-medium text-gray-700">Warna*</label>
                    <input type="text" id="warna" name="warna" value="{{ old('warna', $kendaraan->warna ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('warna')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun*</label>
                    <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $kendaraan->tahun ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tahun')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="nomor_plat" class="block text-sm font-medium text-gray-700">Nomor Plat*</label>
                    <input type="text" id="nomor_plat" name="nomor_plat" value="{{ old('nomor_plat', $kendaraan->nomor_plat ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('nomor_plat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Kolom 2 -->
            <div class="space-y-4">
                <div>
                    <label for="bahan_bakar" class="block text-sm font-medium text-gray-700">Bahan Bakar*</label>
                    <input type="text" id="bahan_bakar" name="bahan_bakar" value="{{ old('bahan_bakar', $kendaraan->bahan_bakar ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('bahan_bakar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="kapasitas_penumpang" class="block text-sm font-medium text-gray-700">Kapasitas Penumpang*</label>
                    <input type="number" id="kapasitas_penumpang" name="kapasitas_penumpang" value="{{ old('kapasitas_penumpang', $kendaraan->kapasitas_penumpang ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('kapasitas_penumpang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="transmisi" class="block text-sm font-medium text-gray-700">Transmisi*</label>
                    <select id="transmisi" name="transmisi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="manual" {{ old('transmisi', $kendaraan->transmisi ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="automatic" {{ old('transmisi', $kendaraan->transmisi ?? '') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                    </select>
                    @error('transmisi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori*</label>
                    <select id="kategori" name="kategori" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="mobil" {{ old('kategori', $kendaraan->kategori ?? '') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="motor" {{ old('kategori', $kendaraan->kategori ?? '') == 'motor' ? 'selected' : '' }}>Motor</option>
                        <option value="truk" {{ old('kategori', $kendaraan->kategori ?? '') == 'truk' ? 'selected' : '' }}>Truk</option>
                        <option value="bus" {{ old('kategori', $kendaraan->kategori ?? '') == 'bus' ? 'selected' : '' }}>Bus</option>
                    </select>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tarif_sewa" class="block text-sm font-medium text-gray-700">Tarif Sewa (per hari)*</label>
                    <input type="number" id="tarif_sewa" name="tarif_sewa" value="{{ old('tarif_sewa', $kendaraan->tarif_sewa ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tarif_sewa')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status*</label>
                <select id="status" name="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="tersedia" {{ old('status', $kendaraan->status ?? '') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="disewa" {{ old('status', $kendaraan->status ?? '') == 'disewa' ? 'selected' : '' }}>Disewa</option>
                    <option value="perbaikan" {{ old('status', $kendaraan->status ?? '') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar*</label>
                @if (isset($kendaraan) && $kendaraan->gambar)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $kendaraan->gambar) }}" alt="Gambar Kendaraan" class="h-32 rounded-md">
                        <p class="mt-1 text-sm text-gray-500">Gambar saat ini</p>
                    </div>
                @endif
                <input type="file" id="gambar" name="gambar" 
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('gambar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6">
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="3" 
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $kendaraan->deskripsi ?? '') }}</textarea>
            @error('deskripsi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-6">
            <label for="fasilitas" class="block text-sm font-medium text-gray-700">Fasilitas (pisahkan dengan koma)</label>
            <input type="text" id="fasilitas" name="fasilitas" value="{{ old('fasilitas', isset($kendaraan) && $kendaraan->fasilitas ? implode(',', $kendaraan->fasilitas) : '') }}" 
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                placeholder="AC, Audio, GPS, dll">
            @error('fasilitas')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-8 flex justify-end">
            <a href="{{ route('kendaraan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                Batal
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Detail Kendaraan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Detail Kendaraan</h2>
            <div>
                <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Gambar dan Status -->
            <div>
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Gambar Kendaraan</h4>
                    <img src="{{ asset('storage/' . $kendaraan->gambar) }}" alt="{{ $kendaraan->merk }}" class="rounded-md border border-gray-200 w-full">
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Status</h4>
                    <form action="{{ route('kendaraan.update-status', $kendaraan) }}" method="POST">
                        @csrf
                        <select name="status" onchange="this.form.submit()" 
                            class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                {{ $kendaraan->status === 'tersedia' ? 'bg-green-100 text-green-800' : 
                                   ($kendaraan->status === 'disewa' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            <option value="tersedia" {{ $kendaraan->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="disewa" {{ $kendaraan->status === 'disewa' ? 'selected' : '' }}>Disewa</option>
                            <option value="perbaikan" {{ $kendaraan->status === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                        </select>
                    </form>
                </div>
            </div>
            
            <!-- Informasi Utama -->
            <div class="md:col-span-2">
                <h3 class="text-2xl font-bold mb-4">{{ $kendaraan->merk }} {{ $kendaraan->model }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tahun</h4>
                        <p class="mt-1">{{ $kendaraan->tahun }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Warna</h4>
                        <p class="mt-1">{{ $kendaraan->warna }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Nomor Plat</h4>
                        <p class="mt-1">{{ $kendaraan->nomor_plat }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Bahan Bakar</h4>
                        <p class="mt-1">{{ $kendaraan->bahan_bakar }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Kapasitas Penumpang</h4>
                        <p class="mt-1">{{ $kendaraan->kapasitas_penumpang }} orang</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Transmisi</h4>
                        <p class="mt-1">{{ $kendaraan->transmisi_label }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Kategori</h4>
                        <p class="mt-1">{{ $kendaraan->kategori_label }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tarif Sewa</h4>
                        <p class="mt-1">Rp{{ number_format($kendaraan->tarif_sewa, 0, ',', '.') }}/hari</p>
                    </div>
                </div>
                
                @if($kendaraan->fasilitas)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Fasilitas</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kendaraan->fasilitas as $fasilitas)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                            {{ $fasilitas }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($kendaraan->deskripsi)
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Deskripsi</h4>
                    <p class="whitespace-pre-line">{{ $kendaraan->deskripsi }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Riwayat Penyewaan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Riwayat Penyewaan</h2>
        </div>
        
        @if($penyewaans->isEmpty())
        <div class="p-6 text-center text-gray-500">
            Kendaraan belum pernah disewa
        </div>
        @else
        <div class="divide-y divide-gray-200">
            @foreach($penyewaans as $penyewaan)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-medium">{{ $penyewaan->pelanggan->nama }}</h4>
                        <p class="text-sm text-gray-500">No. KTP: {{ $penyewaan->pelanggan->no_ktp }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full 
                        {{ $penyewaan->status == 'reservasi' ? 'bg-blue-100 text-blue-800' : 
                           ($penyewaan->status == 'berjalan' ? 'bg-yellow-100 text-yellow-800' : 
                           ($penyewaan->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                        {{ ucfirst($penyewaan->status) }}
                    </span>
                </div>
                <div class="mt-2 flex justify-between text-sm">
                    <span>{{ $penyewaan->tanggal_mulai->format('d M Y') }} - {{ $penyewaan->tanggal_selesai->format('d M Y') }}</span>
                    <span class="font-semibold">Rp{{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $penyewaans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Detail Pelanggan</h2>
            <div>
                @if (!$pelanggan->is_verified)
                    <form action="{{ route('pelanggan.verifikasi', $pelanggan) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Verifikasi
                        </button>
                    </form>
                @endif
                <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informasi Utama -->
            <div class="md:col-span-2">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="flex-shrink-0 h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-2xl">
                        {{ strtoupper(substr($pelanggan->nama, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">{{ $pelanggan->nama }}</h3>
                        <p class="text-gray-600">
                            {{ $pelanggan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, 
                            {{ $pelanggan->umur ? $pelanggan->umur . ' tahun' : '-' }}
                        </p>
                        <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $pelanggan->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $pelanggan->status_verifikasi }}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">No. KTP</h4>
                        <p class="mt-1">{{ $pelanggan->no_ktp }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tanggal Lahir</h4>
                        <p class="mt-1">{{ $pelanggan->tanggal_lahir ? $pelanggan->tanggal_lahir->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Email</h4>
                        <p class="mt-1">{{ $pelanggan->email }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">No. Telepon</h4>
                        <p class="mt-1">{{ $pelanggan->no_telp }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500">Alamat</h4>
                        <p class="mt-1">{{ $pelanggan->alamat }}</p>
                    </div>
                    @if($pelanggan->catatan)
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-500">Catatan</h4>
                        <p class="mt-1">{{ $pelanggan->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Foto KTP -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-2">Foto KTP</h4>
                @if($pelanggan->foto_ktp)
                    <img src="{{ asset('storage/' . $pelanggan->foto_ktp) }}" alt="Foto KTP" class="rounded-md border border-gray-200">
                @else
                    <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center text-gray-500">
                        Tidak ada foto KTP
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Daftar Penyewaan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Riwayat Penyewaan</h2>
        </div>
        
        @if($penyewaans->isEmpty())
        <div class="p-6 text-center text-gray-500">
            Pelanggan belum pernah melakukan penyewaan
        </div>
        @else
        <div class="divide-y divide-gray-200">
            @foreach($penyewaans as $penyewaan)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-medium">{{ $penyewaan->kendaraan->merk }} {{ $penyewaan->kendaraan->model }}</h4>
                        <p class="text-sm text-gray-500">No. Plat: {{ $penyewaan->kendaraan->nomor_plat }}</p>
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
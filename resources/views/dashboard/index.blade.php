@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total Kendaraan</p>
                    <h3 class="text-2xl font-bold">{{ $totalKendaraan }}</h3>
                </div>
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Total Pelanggan</p>
                    <h3 class="text-2xl font-bold">{{ $totalPelanggan }}</h3>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Penyewaan Aktif</p>
                    <h3 class="text-2xl font-bold">{{ $totalPenyewaanAktif }}</h3>
                </div>
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500">Pendapatan Bulan Ini</p>
                    <h3 class="text-2xl font-bold">Rp{{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                </div>
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Two Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Penyewaan Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Penyewaan Terbaru</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($penyewaanTerbaru as $penyewaan)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium">{{ $penyewaan->pelanggan->nama }}</h4>
                            <p class="text-sm text-gray-500">{{ $penyewaan->kendaraan->merk }} {{ $penyewaan->kendaraan->model }}</p>
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
            <div class="p-4 border-t border-gray-200 text-center">
                <a href="{{ route('penyewaan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Penyewaan</a>
            </div>
        </div>
        
        <!-- Kendaraan Populer -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Kendaraan Populer</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($kendaraanPopuler as $kendaraan)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded object-cover" src="{{ asset('storage/' . $kendaraan->gambar) }}" alt="{{ $kendaraan->merk }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium truncate">{{ $kendaraan->merk }} {{ $kendaraan->model }}</h4>
                            <p class="text-sm text-gray-500">{{ $kendaraan->nomor_plat }}</p>
                        </div>
                        <div class="text-right">
                            <span class="font-semibold">{{ $kendaraan->penyewaans_count }}x</span>
                            <p class="text-sm text-gray-500">Disewa</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-gray-200 text-center">
                <a href="{{ route('kendaraan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Kendaraan</a>
            </div>
        </div>
    </div>
    
    <!-- Grafik Pendapatan (Opsional) -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Pendapatan 6 Bulan Terakhir</h3>
            <select class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Tahun Ini</option>
                <option>Tahun Lalu</option>
            </select>
        </div>
        <div class="h-64">
            <!-- Placeholder untuk grafik (bisa menggunakan Chart.js) -->
            <div class="flex items-center justify-center h-full bg-gray-100 rounded">
                <p class="text-gray-500">Grafik Pendapatan</p>
            </div>
        </div>
    </div>
    
</div>
@endsection
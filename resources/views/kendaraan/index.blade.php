@extends('layouts.dashboard')

@section('title', 'Manajemen Kendaraan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-semibold">Daftar Kendaraan</h2>
            
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                <a href="{{ route('kendaraan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kendaraan
                </a>
                
                <form action="{{ route('kendaraan.index') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari kendaraan..." value="{{ request('search') }}" 
                        class="border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-r-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="mb-4 flex flex-wrap gap-2">
            <a href="{{ route('kendaraan.index') }}" class="px-3 py-1 rounded {{ !request('status') && !request('kategori') ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                Semua
            </a>
            
            @foreach(['tersedia', 'disewa', 'perbaikan'] as $status)
            <a href="{{ route('kendaraan.index', ['status' => $status, 'kategori' => request('kategori')]) }}" 
               class="px-3 py-1 rounded {{ request('status') == $status ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ ucfirst($status) }}
            </a>
            @endforeach
            
            <span class="px-2 py-1 text-gray-500">Kategori:</span>
            
            @foreach(['mobil', 'motor', 'truk', 'bus'] as $kategori)
            <a href="{{ route('kendaraan.index', ['kategori' => $kategori, 'status' => request('status')]) }}" 
               class="px-3 py-1 rounded {{ request('kategori') == $kategori ? 'bg-green-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ ucfirst($kategori) }}
            </a>
            @endforeach
        </div>
        
        @if($kendaraans->isEmpty())
        <div class="text-center py-8 text-gray-500">
            Tidak ada data kendaraan
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($kendaraans as $kendaraan)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('storage/' . $kendaraan->gambar) }}" alt="{{ $kendaraan->merk }}" 
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $kendaraan->status === 'tersedia' ? 'bg-green-100 text-green-800' : 
                               ($kendaraan->status === 'disewa' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $kendaraan->status_label['label'] }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">{{ $kendaraan->merk }} {{ $kendaraan->model }}</h3>
                    <p class="text-gray-600 text-sm mb-2">{{ $kendaraan->tahun }} • {{ $kendaraan->warna }}</p>
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">{{ $kendaraan->transmisi_label }} • {{ $kendaraan->kategori_label }}</span>
                        <span class="font-semibold">Rp{{ number_format($kendaraan->tarif_sewa, 0, ',', '.') }}/hari</span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <a href="{{ route('kendaraan.show', $kendaraan) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Detail
                        </a>
                        <div class="flex space-x-2">
                            <a href="{{ route('kendaraan.edit', $kendaraan) }}" class="text-green-600 hover:text-green-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('kendaraan.destroy', $kendaraan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            {{ $kendaraans->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
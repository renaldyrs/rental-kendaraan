@extends('layouts.dashboard')

@section('title', 'Manajemen Penyewaan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-semibold">Daftar Penyewaan</h2>
            
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                <a href="{{ route('penyewaan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Penyewaan
                </a>
                
                <form action="{{ route('penyewaan.index') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari penyewaan..." value="{{ request('search') }}" 
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
            <a href="{{ route('penyewaan.index') }}" class="px-3 py-1 rounded {{ !request('status') && !request('pembayaran') ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                Semua
            </a>
            
            <span class="px-2 py-1 text-gray-500">Status:</span>
            @foreach(['reservasi', 'berjalan', 'selesai', 'batal'] as $status)
            <a href="{{ route('penyewaan.index', ['status' => $status, 'pembayaran' => request('pembayaran')]) }}" 
               class="px-3 py-1 rounded {{ request('status') == $status ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ ucfirst($status) }}
            </a>
            @endforeach
            
            <span class="px-2 py-1 text-gray-500">Pembayaran:</span>
            @foreach(['pending', 'sebagian', 'lunas'] as $pembayaran)
            <a href="{{ route('penyewaan.index', ['pembayaran' => $pembayaran, 'status' => request('status')]) }}" 
               class="px-3 py-1 rounded {{ request('pembayaran') == $pembayaran ? 'bg-green-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ $pembayaran == 'lunas' ? 'Lunas' : ($pembayaran == 'sebagian' ? 'DP' : 'Belum Bayar') }}
            </a>
            @endforeach
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penyewaans as $penyewaan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $penyewaan->pelanggan->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $penyewaan->pelanggan->no_ktp }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $penyewaan->kendaraan->merk }} {{ $penyewaan->kendaraan->model }}</div>
                            <div class="text-sm text-gray-500">{{ $penyewaan->kendaraan->nomor_plat }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $penyewaan->tanggal_mulai->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $penyewaan->tanggal_selesai->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp{{ number_format($penyewaan->total_biaya, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $penyewaan->status === 'reservasi' ? 'bg-blue-100 text-blue-800' : 
                                   ($penyewaan->status === 'berjalan' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($penyewaan->status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $penyewaan->status_label['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $penyewaan->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 
                                   ($penyewaan->status_pembayaran === 'sebagian' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $penyewaan->pembayaran_label['label'] }}
                                @if($penyewaan->status_pembayaran !== 'pending')
                                (Rp{{ number_format($penyewaan->dibayar, 0, ',', '.') }})
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('penyewaan.show', $penyewaan) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                            @if($penyewaan->status === 'reservasi')
                            <a href="{{ route('penyewaan.edit', $penyewaan) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data penyewaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $penyewaans->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
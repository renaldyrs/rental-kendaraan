@extends('layouts.dashboard')

@section('title', 'Laporan Penyewaan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-6">Laporan Penyewaan</h2>
        
        <form action="{{ route('laporan.index') }}" method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="reservasi" {{ $status == 'reservasi' ? 'selected' : '' }}>Reservasi</option>
                        <option value="berjalan" {{ $status == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ $status == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori Kendaraan</label>
                    <select id="kategori" name="kategori" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ $kategori == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="mobil" {{ $kategori == 'mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="motor" {{ $kategori == 'motor' ? 'selected' : '' }}>Motor</option>
                        <option value="truk" {{ $kategori == 'truk' ? 'selected' : '' }}>Truk</option>
                        <option value="bus" {{ $kategori == 'bus' ? 'selected' : '' }}>Bus</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end space-x-2">
                <a href="{{ route('laporan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                    Reset
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
                <a href="{{ route('laporan.export', request()->query()) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Export Excel
                </a>
            </div>
        </form>
        
        <div class="bg-blue-50 p-4 rounded-md mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded shadow">
                    <h4 class="text-sm font-medium text-gray-500">Total Penyewaan</h4>
                    <p class="text-2xl font-bold">{{ $penyewaans->total() }}</p>
                </div>
                <div class="bg-white p-3 rounded shadow">
                    <h4 class="text-sm font-medium text-gray-500">Total Pendapatan</h4>
                    <p class="text-2xl font-bold">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-3 rounded shadow">
                    <h4 class="text-sm font-medium text-gray-500">Periode</h4>
                    <p class="text-lg">
                        {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
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
                    @foreach($penyewaans as $penyewaan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration + ($penyewaans->currentPage() - 1) * $penyewaans->perPage() }}</td>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            Rp{{ number_format($penyewaan->total_biaya, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $penyewaan->status === 'reservasi' ? 'bg-blue-100 text-blue-800' : 
                                   ($penyewaan->status === 'berjalan' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($penyewaan->status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                {{ ucfirst($penyewaan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $penyewaan->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 
                                   ($penyewaan->status_pembayaran === 'sebagian' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($penyewaan->status_pembayaran) }}
                                @if($penyewaan->status_pembayaran !== 'pending')
                                (Rp{{ number_format($penyewaan->dibayar, 0, ',', '.') }})
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('penyewaan.show', $penyewaan) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $penyewaans->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
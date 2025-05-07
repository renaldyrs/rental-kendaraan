@extends('layouts.dashboard')

@section('title', 'Detail Penyewaan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Detail Penyewaan</h2>
            <div class="flex space-x-2">
                @if($penyewaan->status === 'reservasi')
                <form action="{{ route('penyewaan.start', $penyewaan) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Mulai Penyewaan
                    </button>
                </form>
                @endif
                
                @if($penyewaan->status === 'berjalan')
                <form action="{{ route('penyewaan.complete', $penyewaan) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Selesaikan
                    </button>
                </form>
                @endif
                
                @if(in_array($penyewaan->status, ['reservasi', 'berjalan']))
                <form action="{{ route('penyewaan.cancel', $penyewaan) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan penyewaan ini?')">
                        Batalkan
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Informasi Penyewaan -->
            <div class="md:col-span-2 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Pelanggan</h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($penyewaan->pelanggan->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium">{{ $penyewaan->pelanggan->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $penyewaan->pelanggan->no_ktp }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Kendaraan</h4>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $penyewaan->kendaraan->gambar) }}" alt="{{ $penyewaan->kendaraan->merk }}" class="h-10 w-10 rounded-md object-cover">
                            </div>
                            <div>
                                <p class="font-medium">{{ $penyewaan->kendaraan->merk }} {{ $penyewaan->kendaraan->model }}</p>
                                <p class="text-sm text-gray-500">{{ $penyewaan->kendaraan->nomor_plat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tanggal Mulai</h4>
                        <p class="mt-1">{{ $penyewaan->tanggal_mulai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Tanggal Selesai</h4>
                        <p class="mt-1">{{ $penyewaan->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Durasi</h4>
                        <p class="mt-1">{{ $penyewaan->durasi }} hari</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Metode Pembayaran</h4>
                        <p class="mt-1">{{ ucfirst($penyewaan->metode_pembayaran) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $penyewaan->status === 'reservasi' ? 'bg-blue-100 text-blue-800' : 
                               ($penyewaan->status === 'berjalan' ? 'bg-yellow-100 text-yellow-800' : 
                               ($penyewaan->status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                            {{ $penyewaan->status_label['label'] }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Catatan</h4>
                        <p class="mt-1">{{ $penyewaan->catatan ?? '-' }}</p>
                    </div>
                </div>
                
                <!-- Form Pembayaran -->
                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-lg font-medium mb-4">Pembayaran</h4>
                    
                    <div class="bg-gray-50 p-4 rounded-md mb-4">
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <div>
                                <h5 class="text-sm font-medium text-gray-500">Total Biaya</h5>
                                <p class="text-lg font-semibold">Rp{{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <h5 class="text-sm font-medium text-gray-500">Telah Dibayar</h5>
                                <p class="text-lg font-semibold">Rp{{ number_format($penyewaan->dibayar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Status Pembayaran</h5>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $penyewaan->status_pembayaran === 'lunas' ? 'bg-green-100 text-green-800' : 
                                   ($penyewaan->status_pembayaran === 'sebagian' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $penyewaan->pembayaran_label['label'] }}
                            </span>
                        </div>
                    </div>
                    
                    @if($penyewaan->status_pembayaran !== 'lunas')
                    <form action="{{ route('penyewaan.payment', $penyewaan) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran</label>
                                <input type="text" id="jumlah" name="jumlah" min="1" max="{{ $penyewaan->total_biaya - $penyewaan->dibayar }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded h-10 w-full">
                                    Catat Pembayaran
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                    
                    @if($penyewaan->bukti_pembayaran)
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-500 mb-2">Bukti Pembayaran</h5>
                        <img src="{{ asset('storage/' . $penyewaan->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="h-32 rounded-md border border-gray-200">
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Riwayat Pembayaran -->
            <div>
                <h4 class="text-lg font-medium mb-4">Riwayat Pembayaran</h4>
                
                @if(optional($penyewaan->pembayarans)->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    Belum ada riwayat pembayaran
                </div>
                @else
                <div class="space-y-4">
                    @foreach($penyewaan->pembayarans as $payment)
                    <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium">Rp{{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                Berhasil
                            </span>
                        </div>
                        @if($payment->bukti_pembayaran)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                Lihat Bukti
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
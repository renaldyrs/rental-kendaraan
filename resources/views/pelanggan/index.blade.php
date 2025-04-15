@extends('layouts.dashboard')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-semibold">Daftar Pelanggan</h2>
            
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                <a href="{{ route('pelanggan.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Pelanggan
                </a>
                
                <form action="{{ route('pelanggan.index') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari pelanggan..." value="{{ request('search') }}" 
                        class="border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-r-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="mb-4 flex space-x-2">
            <a href="{{ route('pelanggan.index') }}" class="px-3 py-1 rounded {{ !request('verifikasi') ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                Semua
            </a>
            <a href="{{ route('pelanggan.index', ['verifikasi' => 1]) }}" class="px-3 py-1 rounded {{ request('verifikasi') == '1' ? 'bg-green-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                Terverifikasi
            </a>
            <a href="{{ route('pelanggan.index', ['verifikasi' => 0]) }}" class="px-3 py-1 rounded {{ request('verifikasi') == '0' ? 'bg-yellow-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                Belum Terverifikasi
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. KTP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pelanggans as $pelanggan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($pelanggan->nama, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $pelanggan->nama }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $pelanggan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, 
                                        {{ $pelanggan->umur ? $pelanggan->umur . ' tahun' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $pelanggan->no_telp }}</div>
                            <div class="text-sm text-gray-500">{{ $pelanggan->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pelanggan->no_ktp }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $pelanggan->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $pelanggan->status_verifikasi }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('pelanggan.show', $pelanggan) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                            <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="text-green-600 hover:text-green-900 mr-3">Edit</a>
                            <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pelanggan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $pelanggans->links() }}
        </div>
    </div>
</div>
@endsection
@extends('layouts.dashboard')



@section('content')
<div class="bg-white rounded-lg shadow p-6">
   
    
    <form method="POST" action="{{ route('pelanggan.update', $pelanggan) }}" enctype="multipart/form-data">
        @csrf
        @if ($method ?? false)
            @method($method)
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $pelanggan->nama ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="no_ktp" class="block text-sm font-medium text-gray-700">Nomor KTP</label>
                    <input type="text" id="no_ktp" name="no_ktp" value="{{ old('no_ktp', $pelanggan->no_ktp ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('no_ktp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($pelanggan->tanggal_lahir ?? null)->format('Y-m-d')) }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <div class="mt-1 space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="jenis_kelamin" value="L" 
                                {{ old('jenis_kelamin', $pelanggan->jenis_kelamin ?? '') == 'L' ? 'checked' : '' }} 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="jenis_kelamin" value="P" 
                                {{ old('jenis_kelamin', $pelanggan->jenis_kelamin ?? '') == 'P' ? 'checked' : '' }} 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                        </label>
                    </div>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Kolom 2 -->
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $pelanggan->email ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $pelanggan->no_telp ?? '') }}" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('no_telp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="foto_ktp" class="block text-sm font-medium text-gray-700">Foto KTP</label>
                    @if (isset($pelanggan) && $pelanggan->foto_ktp)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $pelanggan->foto_ktp) }}" alt="Foto KTP" class="h-32 rounded-md">
                            <p class="mt-1 text-sm text-gray-500">Foto KTP saat ini</p>
                        </div>
                    @endif
                    <input type="file" id="foto_ktp" name="foto_ktp" 
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('foto_ktp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea id="catatan" name="catatan" rows="2" 
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('catatan', $pelanggan->catatan ?? '') }}</textarea>
            @error('catatan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-8 flex justify-end">
            <a href="{{ route('pelanggan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                Batal
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
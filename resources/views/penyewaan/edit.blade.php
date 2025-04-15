@extends('layouts.dashboard')

@section('title', 'Tambah Penyewaan')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-6">Tambah Penyewaan</h2>
    
    <form method="POST" action="{{ route('penyewaan.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-4">
                <div>
                    <label for="pelanggan_id" class="block text-sm font-medium text-gray-700">Pelanggan*</label>
                    <select id="pelanggan_id" name="pelanggan_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                            {{ $pelanggan->nama }} ({{ $pelanggan->no_ktp }})
                        </option>
                        @endforeach
                    </select>
                    @error('pelanggan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="kendaraan_id" class="block text-sm font-medium text-gray-700">Kendaraan*</label>
                    <select id="kendaraan_id" name="kendaraan_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kendaraan</option>
                        @foreach($kendaraans as $kendaraan)
                        <option value="{{ $kendaraan->id }}" 
                            {{ old('kendaraan_id', $kendaraan->id ?? '') == $kendaraan->id ? 'selected' : '' }}
                            data-tarif="{{ $kendaraan->tarif_sewa }}">
                            {{ $kendaraan->merk }} {{ $kendaraan->model }} ({{ $kendaraan->nomor_plat }}) - Rp{{ number_format($kendaraan->tarif_sewa, 0, ',', '.') }}/hari
                        </option>
                        @endforeach
                    </select>
                    @error('kendaraan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai*</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $tanggal_mulai ?? '') }}" min="{{ date('Y-m-d') }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai*</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $tanggal_selesai ?? '') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Kolom 2 -->
            <div class="space-y-4">
                <div>
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran*</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Metode</option>
                        <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="kredit" {{ old('metode_pembayaran') == 'kredit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                    @error('metode_pembayaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="jumlah_pembayaran" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran Awal (Optional)</label>
                    <input type="number" id="jumlah_pembayaran" name="jumlah_pembayaran" value="{{ old('jumlah_pembayaran') }}" min="0"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('jumlah_pembayaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700">Bukti Pembayaran (Jika ada)</label>
                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('bukti_pembayaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="2"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="bg-blue-50 p-4 rounded-md">
                    <h4 class="text-sm font-medium text-blue-800">Ringkasan Biaya</h4>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <span class="text-sm text-gray-600">Tarif per hari:</span>
                        <span class="text-sm font-medium" id="tarif-per-hari">Rp0</span>
                        
                        <span class="text-sm text-gray-600">Durasi:</span>
                        <span class="text-sm font-medium" id="durasi">0 hari</span>
                        
                        <span class="text-sm text-gray-600">Total Biaya:</span>
                        <span class="text-sm font-medium" id="total-biaya">Rp0</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <a href="{{ route('penyewaan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                Batal
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Simpan Penyewaan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kendaraanSelect = document.getElementById('kendaraan_id');
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const tarifPerHari = document.getElementById('tarif-per-hari');
    const durasi = document.getElementById('durasi');
    const totalBiaya = document.getElementById('total-biaya');
    
    function hitungBiaya() {
        const selectedOption = kendaraanSelect.options[kendaraanSelect.selectedIndex];
        const tarif = selectedOption ? parseFloat(selectedOption.getAttribute('data-tarif')) || 0 : 0;
        
        const mulai = new Date(tanggalMulai.value);
        const selesai = new Date(tanggalSelesai.value);
        
        let hari = 0;
        if (!isNaN(mulai.getTime()) && !isNaN(selesai.getTime())) {
            const diffTime = Math.abs(selesai - mulai);
            hari = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        }
        
        tarifPerHari.textContent = 'Rp' + tarif.toLocaleString('id-ID');
        durasi.textContent = hari + ' hari';
        totalBiaya.textContent = 'Rp' + (tarif * hari).toLocaleString('id-ID');
    }
    
    kendaraanSelect.addEventListener('change', hitungBiaya);
    tanggalMulai.addEventListener('change', function() {
        if (tanggalMulai.value && !tanggalSelesai.value) {
            const nextDay = new Date(tanggalMulai.value);
            nextDay.setDate(nextDay.getDate() + 1);
            tanggalSelesai.valueAsDate = nextDay;
        }
        hitungBiaya();
    });
    tanggalSelesai.addEventListener('change', hitungBiaya);
    
    // Hitung awal jika ada nilai
    hitungBiaya();
});
</script>
@endsection
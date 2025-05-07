@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Vehicles Card -->
            <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Kendaraan</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $totalKendaraan }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Tersedia: {{ $kendaraanTersedia ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Customers Card -->
            <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Pelanggan</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $totalPelanggan }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Baru bulan ini: {{ $pelangganBaruBulanIni ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Rentals Card -->
            <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Penyewaan Aktif</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $totalPenyewaanAktif }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Akan berakhir: {{ $penyewaanAkanBerakhir ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Card -->
            <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Pendapatan Bulan Ini</p>
                        <h3 class="text-2xl font-bold mt-1">Rp{{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-400 mt-1">
                            @if($persentasePendapatan >= 0)
                                <span class="text-green-500">↑ {{ $persentasePendapatan }}%</span> dari bulan lalu
                            @else
                                <span class="text-red-500">↓ {{ abs($persentasePendapatan) }}%</span> dari bulan lalu
                            @endif
                        </p>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Rentals -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Penyewaan Terbaru</h3>
                    <a href="{{ route('penyewaan.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        Lihat Semua
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($penyewaanTerbaru as $penyewaan)
                                <a href="{{ route('penyewaan.show', $penyewaan->id) }}"
                                    class="block p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $penyewaan->pelanggan->nama }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $penyewaan->kendaraan->merk }}
                                                {{ $penyewaan->kendaraan->model }}</p>
                                        </div>
                                        <span
                                            class="px-3 py-1 text-xs rounded-full 
                                            {{ $penyewaan->status == 'reservasi' ? 'bg-blue-100 text-blue-800' :
                        ($penyewaan->status == 'berjalan' ? 'bg-yellow-100 text-yellow-800' :
                            ($penyewaan->status == 'selesai' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ ucfirst($penyewaan->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-3 flex justify-between text-sm">
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ $penyewaan->tanggal_mulai->format('d M Y') }} -
                                            {{ $penyewaan->tanggal_selesai->format('d M Y') }}
                                        </div>
                                        <span class="font-semibold">Rp{{ number_format($penyewaan->total_biaya, 0, ',', '.') }}</span>
                                    </div>
                                </a>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Tidak ada data penyewaan terbaru
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Popular Vehicles -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Kendaraan Populer</h3>
                    <a href="{{ route('kendaraan.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        Lihat Semua
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($kendaraanPopuler as $kendaraan)
                        <a href="{{ route('kendaraan.show', $kendaraan->id) }}"
                            class="block p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded object-cover"
                                        src="{{ $kendaraan->gambar ? asset('storage/' . $kendaraan->gambar) : asset('images/default-vehicle.png') }}"
                                        alt="{{ $kendaraan->merk }}" loading="lazy">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 truncate">{{ $kendaraan->merk }}
                                        {{ $kendaraan->model }}</h4>
                                    <p class="text-sm text-gray-500">{{ $kendaraan->nomor_plat }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold">{{ $kendaraan->penyewaans_count }}x</span>
                                    <p class="text-xs text-gray-500">Disewa</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            Tidak ada data kendaraan populer
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Pendapatan Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Grafik Pendapatan (6 Bulan Terakhir)</h3>
                <canvas id="pendapatanChart" height="200"></canvas>
            </div>

            <!-- Penyewaan Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Grafik Jumlah Penyewaan (6 Bulan Terakhir)</h3>
                <canvas id="penyewaanChart" height="200"></canvas>
            </div>
        </div>

    </div>

    @push('scripts')
<script>
    // Pendapatan Chart
    const ctxPendapatan = document.getElementById('pendapatanChart').getContext('2d');
    const pendapatanChart = new Chart(ctxPendapatan, {
        type: 'line',
        data: {
            labels: @json($chartLabels), // Menampilkan bulan-bulan (M Y)
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartValues), // Menampilkan total pendapatan untuk masing-masing bulan
                borderColor: '#7C3AED',
                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString(); // Format Rupiah
                        }
                    }
                }
            }
        }
    });

    // Penyewaan Chart
    const ctxPenyewaan = document.getElementById('penyewaanChart').getContext('2d');
    const penyewaanChart = new Chart(ctxPenyewaan, {
        type: 'bar',
        data: {
            labels: @json($chartLabels), // Menampilkan bulan-bulan (M Y)
            datasets: [{
                label: 'Jumlah Penyewaan',
                data: @json($chartValues), // Menampilkan total penyewaan untuk masing-masing bulan
                backgroundColor: '#10B981'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endpush

@endsection
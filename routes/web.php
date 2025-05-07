<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';



// Route untuk home
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes lainnya
    Route::resource('kendaraan', KendaraanController::class);
    Route::post('/kendaraan/{kendaraan}/update-status', [KendaraanController::class, 'updateStatus'])
    ->name('kendaraan.update-status');

    Route::resource('pelanggan', PelangganController::class);
    Route::resource('penyewaan', PenyewaanController::class);
});

// Route to display the form (GET request)
Route::post('pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');

// Route to handle form submission (POST request)
Route::post('pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
Route::post('/pelanggan/{pelanggan}/verifikasi', [PelangganController::class, 'verifikasi'])
    ->name('pelanggan.verifikasi')
    ->middleware('can:verifikasi,pelanggan');

    Route::resource('penyewaan', PenyewaanController::class);

// Additional routes
Route::post('/penyewaan/{penyewaan}/start', [PenyewaanController::class, 'start'])
    ->name('penyewaan.start');
Route::post('/penyewaan/{penyewaan}/complete', [PenyewaanController::class, 'complete'])
    ->name('penyewaan.complete');
Route::post('/penyewaan/{penyewaan}/cancel', [PenyewaanController::class, 'cancel'])
    ->name('penyewaan.cancel');
Route::post('/penyewaan/{penyewaan}/payment', [PenyewaanController::class, 'payment'])
    ->name('penyewaan.payment');

    Route::prefix('laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/export', [LaporanController::class, 'export'])->name('laporan.export');
    });
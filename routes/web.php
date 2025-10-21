<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;

Route::view('/', 'welcome');

/* STAFF ONLY */
Route::middleware('auth:staff')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Halaman tambah barang sesuai desain
    Route::get('/tambah-barang', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/tambah-barang', [BarangController::class, 'store'])->name('barang.store');

    // CRUD lain
    Route::resource('barang', BarangController::class)->except(['show','create','store']);

    // Redirect link lama
    Route::redirect('/tambah barang', '/tambah-barang'); // handle /tambah%20barang
    Route::get('/tambah', fn() => redirect()->route('barang.create'))->name('tambah');
});

/* PELANGGAN */
Route::get('/pelanggan/register', [PelangganAuthController::class, 'showRegisterForm'])
    ->name('pelanggan.register');

Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])
    ->name('pelanggan.register.store');

Route::middleware('auth:pelanggan')->group(function () {
    Route::view('/home', 'pelanggan.dashboard')->name('home');
});

/* PROFILE (guard default) */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* AUTH */
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// UI EDIT & HAPUS (sesuai sidebar)
Route::get('/edit',  [\App\Http\Controllers\BarangController::class, 'editPage'])->name('ui.edit');
Route::get('/hapus', [\App\Http\Controllers\BarangController::class, 'deletePage'])->name('ui.hapus');

// Quick update satu kolom (ID/Nama/Tanggal/Stok)
Route::post('/barang/quick-update', [\App\Http\Controllers\BarangController::class, 'quickUpdate'])
    ->name('barang.quickUpdate');

// Hapus per-row (resource destroy juga boleh)
Route::delete('/barang/{barang}', [\App\Http\Controllers\BarangController::class, 'destroy'])
    ->name('barang.destroy');

Route::middleware('auth:staff')->group(function () {
    Route::get('/laporan/barang', [LaporanController::class, 'barang'])->name('laporan.barang');
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
});

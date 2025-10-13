<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController; // Controller untuk login gabungan
use App\Http\Controllers\PelangganAuthController; // Controller untuk register pelanggan

/*
|----------------------------------------------------------------------
| Web Routes
|----------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

/* Dashboard Staff (Admin Panel) */
Route::get('/dashboard', function () {
    // Pastikan hanya staff yang bisa mengakses dashboard admin
    if (!session()->has('staff_id')) return redirect('/login');
    return view('dashboard');  // Dashboard Admin (Filament)
})->name('dashboard');

/* Dashboard Pelanggan (Dashboard Katalog) */
Route::get('/pelanggan/dashboard', function () {
    // Pastikan hanya pelanggan yang bisa mengakses dashboard katalog
    if (!session()->has('pelanggan_id')) return redirect('/login');
    return view('pelanggan.dashboard');  // Dashboard Pelanggan (Katalog)
})->name('pelanggan.dashboard');

/* Profil Pengguna (Opsional, jika menggunakan Laravel Profile) */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ===== LOGIN GABUNGAN (Semua Aktor Lewat /login) ===== */
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form'); // Form login
Route::post('/login', [AuthController::class, 'login'])->name('login'); // Proses login
Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // Logout

/* ===== REGISTER PELANGGAN ===== */
Route::get('/pelanggan/register', [PelangganAuthController::class, 'showRegisterForm'])->name('pelanggan.register'); // Form register pelanggan
Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])->name('pelanggan.register.store'); // Proses register pelanggan

/* Paket Auth Bawaan Laravel (Jika Tidak Bentrok Dengan /login, Bisa Dihapus) */
require __DIR__.'/auth.php';


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'));

// =============================
// DASHBOARD (STAFF SAJA)
// =============================
Route::middleware('auth:staff')->group(function () {
    Route::get('/dashboard', function () {
        // View untuk staf (pastikan file ini ada)
        return view('dashboard'); // resources/views/dashboard.blade.php
    })->name('dashboard');
});

// =============================
// HOME (PELANGGAN / USERS SAJA)
// =============================
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/home', function () {
        // Pakai view pelanggan/dashboard yang sudah ada
        // Jika kamu punya home.blade.php sendiri, ganti ke: return view('home');
        return view('pelanggan.dashboard'); // resources/views/pelanggan/dashboard.blade.php
    })->name('home');
});

// =============================
// PROFIL (opsional; guard default mengikuti config/auth.php)
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================
// LOGIN / LOGOUT (AuthController)
// =============================

// Form login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

// Proses login (AuthController akan arahkan: staff -> /dashboard, pelanggan -> /home)
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Logout (keluar dari guard staff/pelanggan)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================
// REGISTER PELANGGAN
// =============================
Route::get('/pelanggan/register', [PelangganAuthController::class, 'showRegisterForm'])
    ->name('pelanggan.register');

Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])
    ->name('pelanggan.register.store');

// Jika masih butuh route bawaan Laravel, biarkan baris ini.
// Kalau bentrok dengan /login custom kamu, boleh dihapus.
// require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PelangganAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', function () {
    if (!session()->has('staff_id')) return redirect('/login');
    return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* 🔐 STAFF AUTH (nama: login.form) */
Route::get('/login', [StaffController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [StaffController::class, 'login'])->name('login');
Route::get('/logout', [StaffController::class, 'logout'])->name('logout');

/* 👤 PELANGGAN AUTH */
Route::get('/pelanggan/register', [PelangganAuthController::class, 'showRegisterForm'])->name('pelanggan.register');
Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])->name('pelanggan.register.store');

Route::get('/pelanggan/login', [PelangganAuthController::class, 'showLoginForm'])->name('pelanggan.login.form');
Route::post('/pelanggan/login', [PelangganAuthController::class, 'login'])->name('pelanggan.login');
Route::get('/pelanggan/logout', [PelangganAuthController::class, 'logout'])->name('pelanggan.logout');

Route::get('/pelanggan/dashboard', function () {
    if (!session()->has('pelanggan_id')) return redirect()->route('pelanggan.login.form');
    return view('pelanggan.dashboard');
})->name('pelanggan.dashboard');

/* Breeze/Jetstream routes (biarkan paling bawah) */
require __DIR__.'/auth.php';

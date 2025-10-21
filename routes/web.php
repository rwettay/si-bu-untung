<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;

/*
|-------------------------------------------------------------------------- 
| Web Routes 
|-------------------------------------------------------------------------- 
*/

Route::get('/', fn () => view('welcome'))->name('welcome');

/* ===================== AUTH (Custom) ===================== */
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* ================== REGISTER PELANGGAN =================== */
Route::get('/pelanggan/register',  [PelangganAuthController::class, 'showRegisterForm'])
    ->name('pelanggan.register');
Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])
    ->name('pelanggan.register.store');

/* ===================== STAFF / ADMIN ===================== */
Route::middleware(['auth:staff'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

/* ===================== PELANGGAN ========================= */
Route::middleware(['auth:pelanggan'])->group(function () {
    Route::get('/home', [CustomerHomeController::class, 'index'])->name('customer.home');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    // Menampilkan halaman keranjang (cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // Profil pelanggan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id_barang}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});



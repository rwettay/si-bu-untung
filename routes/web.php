<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\BarangController;
use App\Models\Barang;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'));

/* =============================
 * DASHBOARD (STAFF SAJA)
 * ============================= */
Route::middleware('auth:staff')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // resources/views/dashboard.blade.php
    })->name('dashboard');

    /* ============================
     *  Halaman UI sesuai sidebar
     * ============================ */
    // /tambah -> form UI (pakai komponen layout sidebar)
    Route::get('/tambah', function () {
        $barang = new Barang(); // untuk form kosong (kalau perlu)
        return view('barang.tambah', compact('barang')); // atau 'barang.form' kalau kamu satukan
    })->name('ui.tambah');

    // /edit -> halaman UI seperti mockup (judul "Cari barang yang ingin di edit")
    Route::get('/edit', function () {
        $barangs = Barang::orderBy('nama_barang')->paginate(8);
        return view('barang.edit', compact('barangs'));
    })->name('ui.edit');

    // /hapus -> halaman UI seperti mockup hapus
    Route::get('/hapus', function () {
        $barangs = Barang::orderBy('nama_barang')->paginate(8);
        return view('barang.hapus', compact('barangs'));
    })->name('ui.hapus');

    /* ============================
     *   Kelola Barang (CRUD DB)
     * ============================
     *  GET    /barang                 -> barang.index
     *  GET    /barang/create          -> barang.create
     *  POST   /barang                 -> barang.store
     *  GET    /barang/{barang}/edit   -> barang.edit
     *  PUT    /barang/{barang}        -> barang.update
     *  DELETE /barang/{barang}        -> barang.destroy
     *  (tanpa show)
     */
    Route::resource('barang', BarangController::class)
        ->parameters(['barang' => 'barang']) // binding by 'id_barang' (lihat model)
        ->except(['show']);
});

/* =============================
 * HOME (PELANGGAN / USERS SAJA)
 * ============================= */
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/home', function () {
        return view('pelanggan.dashboard');
    })->name('home');
});

/* =============================
 * PROFIL (opsional; guard default sesuai config/auth.php)
 * ============================= */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* =============================
 * LOGIN / LOGOUT (AuthController)
 * ============================= */
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/* =============================
 * REGISTER PELANGGAN
 * ============================= */
Route::get('/pelanggan/register', [PelangganAuthController::class, 'showRegisterForm'])
    ->name('pelanggan.register');
Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])
    ->name('pelanggan.register.store');

// require __DIR__ . '/auth.php'; // nonaktifkan jika bentrok dengan /login custom

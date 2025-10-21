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
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    /* ============================
     *  Halaman UI sesuai sidebar
     * ============================ */

    // ====== TAMBAH (UI MOCKUP) ======
    // View: resources/views/barang/tambah-page.blade.php
    Route::get('/tambah', function () {
        $barang = new Barang();
        return view('barang.tambah', compact('barang'));
    })->name('ui.tambah');

    // Aksi simpan dari UI Tambah (tanpa lewat create/store bawaan)
    Route::post('/barang/quick-store', [BarangController::class, 'quickStore'])
        ->name('barang.quick.store');

    // ====== EDIT (UI MOCKUP) ======
    // View: resources/views/barang/edit-page.blade.php
    Route::get('/edit', [BarangController::class, 'quickEditPage'])
        ->name('ui.edit');

    // Aksi update cepat 1 field (nama/tanggal/stok) dari kartu mini
    Route::post('/barang/quick-update', [BarangController::class, 'quickUpdate'])
        ->name('barang.quick.update');

    // ====== HAPUS (UI MOCKUP) ======
    // View: resources/views/barang/hapus-page.blade.php
    Route::get('/hapus', function () {
        $q = request('q');
        $barangs = Barang::query()
            ->when($q, function ($qq) use ($q) {
                $qq->where('id_barang', 'like', "%{$q}%")
                   ->orWhere('nama_barang', 'like', "%{$q}%");
            })
            ->orderBy('id_barang')
            ->paginate(8)
            ->withQueryString();

        return view('barang.hapus', compact('barangs', 'q'));
    })->name('ui.hapus');

    /* ============================
     *   ROUTE RESOURCE (CRUD DB)
     * ============================
     *  GET    /barang               -> barang.index
     *  GET    /barang/create        -> barang.create
     *  POST   /barang               -> barang.store
     *  GET    /barang/{barang}/edit -> barang.edit
     *  PUT    /barang/{barang}      -> barang.update
     *  DELETE /barang/{barang}      -> barang.destroy
     */
    Route::resource('barang', BarangController::class)
        ->parameters(['barang' => 'barang']) // binding pakai id_barang (lihat model->getRouteKeyName)
        ->except(['show']);
});

/* =============================
 * HOME (PELANGGAN / USERS SAJA)
 * ============================= */
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/home', fn () => view('pelanggan.dashboard'))->name('home');
});

/* =============================
 * PROFIL (opsional)
 * ============================= */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* =============================
 * LOGIN / LOGOUT
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

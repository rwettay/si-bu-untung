<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelangganAuthController extends Controller
{
    /**
     * Tampilkan form register pelanggan.
     */
    public function showRegisterForm()
    {
        // Sesuaikan dengan lokasi view-mu
        return view('auth.register'); // atau 'pelanggan.auth.register'
    }

    /**
     * Proses register pelanggan.
     */
    public function register(Request $r)
    {
        $data = $r->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp'          => 'required|string|max:20',
            'username'       => 'required|string|max:50|alpha_dash|unique:pelanggan,username',
            'email'          => 'required|email|unique:pelanggan,email',
            // tidak pakai confirmed
            'password'       => 'required|string|min:6',
            'alamat'         => 'nullable|string|max:255',
        ]);

        // Buat pelanggan (ID PLGxxxxxx otomatis dari Model::booted)
        $pel = DB::transaction(function () use ($data) {
            return Pelanggan::create([
                'nama_pelanggan' => $data['nama_pelanggan'],
                'no_hp'          => $data['no_hp'],
                'username'       => $data['username'],
                'email'          => $data['email'],
                // Hash eksplisit (meski model punya mutator, ini aman)
                'password'       => Hash::make($data['password']),
                'alamat'         => $data['alamat'] ?? null,
            ]);
        });

        // Login dengan guard 'pelanggan'
        Auth::guard('pelanggan')->login($pel);
        $r->session()->regenerate();

        return redirect()->route('customer.home')->with('success', 'Registrasi berhasil!');
    }
}

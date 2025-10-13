<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PelangganAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $r)
    {
        $data = $r->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp'          => 'required|string|max:20',
            'username'       => 'required|string|max:50|alpha_dash|unique:pelanggan,username',
            'email'          => 'required|email:rfc,dns|max:100|unique:pelanggan,email',
            'password'       => 'required|string|min:6',
            'alamat'         => 'nullable|string',
        ]);

        Pelanggan::create([
            'id_pelanggan'   => 'C' . Str::upper(Str::ulid()),
            'nama_pelanggan' => $data['nama_pelanggan'],
            'no_hp'          => $data['no_hp'],
            'username'       => $data['username'],
            'email'          => $data['email'],
            'password'       => $data['password'], // di-hash oleh mutator model
            'alamat'         => $data['alamat'] ?? null,
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}

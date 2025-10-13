<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Pelanggan;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');  // Menampilkan form login
    }

public function login(Request $request)
{
    $request->validate([
        'identifier' => 'required|string',
        'password'   => 'required|string',
    ]);

    $identifier = $request->identifier;
    $password   = $request->password;

    // Tentukan model dan guard berdasarkan identifier (email atau username)
    $user = null;
    $guard = null;

    // Cek apakah identifier adalah email atau username
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // Cari di model staff atau pelanggan berdasarkan email
        $user = Staff::where('email', $identifier)->first();
        $guard = 'staff'; // Menggunakan guard staff untuk admin
    } else {
        // Jika bukan email, cek apakah identifier adalah username untuk staff atau pelanggan
        $user = Staff::where('username', $identifier)->first();
        if (!$user) {
            // Jika bukan staff, cek di model pelanggan
            $user = Pelanggan::where('username', $identifier)->first();
            $guard = 'pelanggan';  // Guard pelanggan untuk katalog
        } else {
            $guard = 'staff';  // Guard staff untuk admin
        }
    }

    // Jika user tidak ditemukan
    if (!$user) {
        return back()->withErrors(['identifier' => 'Username atau Email tidak ditemukan.']);
    }

    // Cek password menggunakan Hash::check
    if (!Hash::check($password, $user->password)) {
        return back()->withErrors(['password' => 'Password salah.']);
    }

    // Login menggunakan guard yang sesuai (staff atau pelanggan)
    Auth::guard($guard)->login($user);

    // Redirect ke halaman yang sesuai berdasarkan guard
    if ($guard === 'staff') {
        return redirect()->route('filament.pages.dashboard');  // Halaman admin Filament
    } else {
        return redirect()->route('pelanggan.dashboard');  // Halaman katalog pelanggan
    }
}


    public function logout()
    {
        Auth::logout(); // Logout user
        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Pelanggan;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login untuk staff & pelanggan (gabungan).
     * - Staff  -> redirect ke /dashboard
     * - Pelanggan/Users -> redirect ke /home
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ]);

        $identifier = $request->input('identifier');
        $password   = $request->input('password');

        // Tentukan user & guard berdasarkan identifier (email atau username)
        $user  = null;
        $guard = null;

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // Jika identifier berupa email, prioritas cek ke Staff dulu
            $user  = Staff::where('email', $identifier)->first();
            $guard = $user ? 'staff' : null;

            // Jika tidak ditemukan di Staff, cek ke Pelanggan
            if (!$user) {
                $user  = Pelanggan::where('email', $identifier)->first();
                $guard = $user ? 'pelanggan' : null;
            }
        } else {
            // Jika identifier bukan email, anggap sebagai username
            $user  = Staff::where('username', $identifier)->first();
            $guard = $user ? 'staff' : null;

            if (!$user) {
                $user  = Pelanggan::where('username', $identifier)->first();
                $guard = $user ? 'pelanggan' : null;
            }
        }

        // User tidak ditemukan
        if (!$user || !$guard) {
            return back()->withErrors([
                'identifier' => 'Username atau Email tidak ditemukan.',
            ])->withInput($request->only('identifier'));
        }

        // Verifikasi password
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ])->withInput($request->only('identifier'));
        }

        // Login sesuai guard
        Auth::guard($guard)->login($user);

        // ===== Penting: simpan sesi login & cegah session fixation =====
        $request->session()->regenerate();

        // Redirect sesuai peran
        if ($guard === 'staff') {
            // Staff menuju /dashboard (dengan intended fallback)
            return redirect()->intended('/dashboard');
        }

        // Pelanggan / user biasa menuju /home (dengan intended fallback)
        return redirect()->intended('/home');
    }

    /**
     * Logout untuk kedua guard (staff & pelanggan).
     */
    public function logout(Request $request)
    {
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }

        if (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
        }

        // Hancurkan sesi dan regen CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}

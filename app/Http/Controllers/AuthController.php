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
     * Proses login gabungan:
     * - Staff    : email ATAU username
     * - Pelanggan: email saja
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',   // email atau username
            'password'   => 'required|string',
            // 'remember' => 'sometimes|boolean' // opsional kalau pakai remember me
        ]);

        $identifier = trim($request->input('identifier'));
        $password   = $request->input('password');
        $remember   = (bool) $request->input('remember', false);

        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        $user  = null;
        $guard = null;

        if ($isEmail) {
            // 1) Coba STAFF by email
            $user  = Staff::where('email', $identifier)->first();
            $guard = $user ? 'staff' : null;

            // 2) Kalau tidak ketemu, coba PELANGGAN by email
            if (!$user) {
                $user  = Pelanggan::where('email', $identifier)->first();
                $guard = $user ? 'pelanggan' : null;
            }
        } else {
            // Identifier bukan email -> anggap USERNAME tapi HANYA untuk STAFF
            $user  = Staff::where('username', $identifier)->first();
            $guard = $user ? 'staff' : null;

            // Jangan cari 'username' di tabel pelanggan karena kolomnya tidak ada
        }

        if (!$user || !$guard) {
            return back()
                ->withErrors(['identifier' => 'Username/Email tidak ditemukan.'])
                ->onlyInput('identifier');
        }

        // Verifikasi password
        if (!Hash::check($password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah.'])
                ->onlyInput('identifier');
        }

        // Login sesuai guard
        Auth::guard($guard)->login($user, $remember);
        $request->session()->regenerate();

        return $guard === 'staff'
            ? redirect()->intended('/dashboard')
            : redirect()->intended('/home');
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

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}

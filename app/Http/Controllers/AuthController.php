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
        return view('auth.login');  // form kamu sekarang
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'], // email ATAU username
            'password'   => ['required', 'string'],
        ]);

        $id  = $request->input('identifier');
        $pwd = $request->input('password');

        $user  = null;
        $guard = null;

        $isEmail = filter_var($id, FILTER_VALIDATE_EMAIL);

        // 1) Coba dulu STAFF (email/username)
        $user = $isEmail
            ? Staff::where('email', $id)->first()
            : Staff::where('username', $id)->first();

        if ($user) {
            $guard = 'staff';
        } else {
            // 2) Kalau bukan staff, coba PELANGGAN (email/username)
            $user = $isEmail
                ? Pelanggan::where('email', $id)->first()
                : Pelanggan::where('username', $id)->first();

            if ($user) {
                $guard = 'pelanggan';
            }
        }

        if (!$user) {
            return back()
                ->withErrors(['identifier' => 'Username atau Email tidak ditemukan.'])
                ->onlyInput('identifier');
        }

        if (!Hash::check($pwd, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah.'])
                ->onlyInput('identifier');
        }

        Auth::guard($guard)->login($user);
        $request->session()->regenerate();

        // Redirect konsisten:
        if ($guard === 'staff') {
            // ganti ke route filament-mu jika ada
            return redirect()->intended(route('dashboard'));
            // atau: return redirect()->intended(route('filament.pages.dashboard'));
        }

        // pelanggan → ke /home (route('customer.home')) sesuai setup kita
        return redirect()->intended(route('customer.home'));
    }

    public function logout(Request $request)
    {
        // Logout semua guard yang mungkin terpakai
        foreach (['staff','pelanggan','web'] as $g) {
            if (Auth::guard($g)->check()) {
                Auth::guard($g)->logout();
            }
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}

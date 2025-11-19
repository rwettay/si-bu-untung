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
     * Proses login gabungan untuk Staff & Pelanggan.
     * - Staff    -> redirect ke route('dashboard')
     * - Pelanggan-> redirect ke route('customer.home')
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string'], // email ATAU username
            'password'   => ['required', 'string'],
        ]);

        [$user, $guard] = $this->findUserAndGuard($validated['identifier']);

        if (!$user || !$guard) {
            return back()
                ->withErrors(['identifier' => 'Username atau Email tidak ditemukan.'])
                ->onlyInput('identifier');
        }

        if (!Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah.'])
                ->onlyInput('identifier');
        }

        Auth::guard($guard)->login($user);
        $request->session()->regenerate(); // cegah session fixation

        return $guard === 'staff'
            ? redirect()->intended(route('dashboard')) // Redirect ke dashboard custom
            : redirect()->intended(route('customer.home'));
    }

    /**
     * Logout untuk kedua guard (staff & pelanggan).
     */
    public function logout(Request $request)
    {
        foreach (['staff', 'pelanggan', 'web'] as $g) {
            if (Auth::guard($g)->check()) {
                Auth::guard($g)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    /**
     * Helper: cari user & tentukan guard berdasarkan identifier (email/username).
     *
     * @return array{0: (\App\Models\Staff|\App\Models\Pelanggan|null), 1: ('staff'|'pelanggan'|null)}
     */
    private function findUserAndGuard(string $identifier): array
    {
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        // Prioritaskan Staff terlebih dahulu
        $user = $isEmail
            ? Staff::where('email', $identifier)->first()
            : Staff::where('username', $identifier)->first();

        if ($user) {
            return [$user, 'staff'];
        }

        // Lanjut cek Pelanggan
        $user = $isEmail
            ? Pelanggan::where('email', $identifier)->first()
            : Pelanggan::where('username', $identifier)->first();

        if ($user) {
            return [$user, 'pelanggan'];
        }

        return [null, null];
    }
}

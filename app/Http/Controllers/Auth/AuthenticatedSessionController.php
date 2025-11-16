<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login (kalau masih dipakai).
     */
    public function create(): View
    {
        // Kalau kamu pakai view custom sendiri, arahkan ke situ:
        return view('auth.login');
    }

    /**
     * Tangani POST login dari Breeze.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Akan mencoba guard:staff, lalu guard:pelanggan (lihat LoginRequest)
        $request->authenticate();

        // Regenerate session
        $request->session()->regenerate();

        // Redirect berdasarkan guard yang aktif
        if (Auth::guard('staff')->check()) {
            // ganti ke route dashboard adminmu (Filament dsb)
            return redirect()->intended(route('dashboard'));
            // atau: return redirect()->intended(route('filament.pages.dashboard'));
        }

        if (Auth::guard('pelanggan')->check()) {
            return redirect()->intended(route('customer.home')); // /home
        }

        // fallback (harusnya tidak kepakai)
        return redirect()->intended('/');
    }

    /**
     * Logout (bersihkan semua guard yang mungkin aktif).
     */
    public function destroy(Request $request): RedirectResponse
    {
        foreach (['staff', 'pelanggan', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

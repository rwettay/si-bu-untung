<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Session;

class StaffController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $staff = Staff::where('username', $request->username)->first();

        if (!$staff) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        // Password di seeder masih plaintext, jadi kita bandingkan langsung
        // Nanti bisa diganti hash (bcrypt)
        if ($staff->password !== $request->password) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        // Simpan data ke session
        Session::put('staff_id', $staff->id_staff);
        Session::put('staff_role', $staff->role);
        Session::put('staff_username', $staff->username);

        return redirect()->route('dashboard');
    }

    // Logout
    public function logout()
    {
        Session::flush();
        return redirect()->route('login.form')->with('success', 'Berhasil logout.');
    }
}

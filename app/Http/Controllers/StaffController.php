<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
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
            'identifier' => 'required', // Can be email or username
            'password' => 'required'
        ]);

        // Check if identifier is an email or username
        if (filter_var($request->identifier, FILTER_VALIDATE_EMAIL)) {
            // If it's an email, find staff by email
            $staff = Staff::where('email', $request->identifier)->first();
        } else {
            // If it's not an email, find staff by username
            $staff = Staff::where('username', $request->identifier)->first();
        }

        // If staff not found
        if (!$staff) {
            return back()->withErrors(['identifier' => 'Username or Email not found.']);
        }

        // Check password
        if (!Hash::check($request->password, $staff->password)) {
            return back()->withErrors(['password' => 'Invalid credentials.']);
        }

        // Store staff data in session
        Session::put('staff_id', $staff->id_staff);
        Session::put('staff_role', $staff->role);
        Session::put('staff_username', $staff->username);

        // Redirect to dashboard
        return redirect()->route('dashboard');
    }

    // Logout
    public function logout()
    {
        // Clear all session data
        Session::flush();

        return redirect()->route('login.form')->with('success', 'You have logged out successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerProfileController extends Controller
{
    /**
     * Display the profile edit form.
     */
    public function edit()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('customer.profile.edit', compact('pelanggan'));
    }

    /**
     * Update the profile information.
     */
    public function update(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();

        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('pelanggan', 'username')->ignore($pelanggan->id_pelanggan, 'id_pelanggan')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('pelanggan', 'email')->ignore($pelanggan->id_pelanggan, 'id_pelanggan')],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update data
        $pelanggan->nama_pelanggan = $validated['nama_pelanggan'];
        $pelanggan->username = $validated['username'];
        $pelanggan->email = $validated['email'] ?? null;
        $pelanggan->no_hp = $validated['no_hp'] ?? null;
        $pelanggan->alamat = $validated['alamat'] ?? null;

        // Update password if provided
        if (!empty($validated['password'])) {
            $pelanggan->password = $validated['password']; // Will be hashed by model mutator
        }

        $pelanggan->save();

        return redirect()->route('customer.profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}


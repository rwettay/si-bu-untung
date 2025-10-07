<?php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
class PelangganAuthController extends Controller
{
public function register(Request $r)
{
    $data = $r->validate([
        'nama_pelanggan' => 'required|max:100',
        'no_hp'          => 'required|max:20',
        'username'       => 'required|max:50|unique:pelanggan,username',
        'password'       => 'required|min:6',
        'alamat'         => 'nullable',
    ]);

    $pelanggan = Pelanggan::create([
        'id_pelanggan'   => 'C'.Str::upper(Str::ulid()),
        'nama_pelanggan' => $data['nama_pelanggan'],
        'no_hp'          => $data['no_hp'],
        'username'       => $data['username'],
        'password'       => $data['password'], // kalau pakai mutator di model, biarkan raw
        'alamat'         => $data['alamat'] ?? null,
    ]);

    // ➜ TANPA auto-login: langsung arahkan ke halaman login staff
    return redirect()->route('login.form')
        ->with('success', 'Akun berhasil dibuat. Silakan login.');
}
}

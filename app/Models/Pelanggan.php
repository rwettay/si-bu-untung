<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// (opsional biar lebih pendek pemanggilannya)
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'alamat',
        'no_hp',
        'username',
        'password',
    ];

    protected $hidden = ['password'];

    // ⬇️ MUTATOR Auto-hash password — letakkan di sini (di dalam class)
    public function setPasswordAttribute($value)
    {
        // hanya hash jika belum ter-hash
        if (Str::startsWith($value, '$2y$')) { // bcrypt prefix
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // (opsional) relasi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }
}

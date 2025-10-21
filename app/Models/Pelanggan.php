<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // penting
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Pelanggan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    // Biarkan ini kalau PK-mu string manual seperti "PLG001".
    // Jika PK auto-increment INT, hapus dua baris ini.
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pelanggan','nama_pelanggan','alamat','no_hp','username','email','password',
    ];

    protected $hidden = ['password','remember_token'];

    // Auto-hash password (dipanggil saat set atribut password)
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Str::startsWith((string)$value, '$2y$')
            ? $value
            : Hash::make($value);
    }

    // Relasi contoh
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatPelanggan extends Model
{
    use HasFactory;

    protected $table = 'alamat_pelanggan';

    protected $fillable = [
        'id_pelanggan',
        'label',
        'nama_penerima',
        'telepon',
        'alamat_lengkap',
        'catatan',
        'lat',
        'lng',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    /**
     * Relasi: alamat milik satu pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Scope: ambil alamat default
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope: ambil alamat milik pelanggan tertentu
     */
    public function scopeForPelanggan($query, $idPelanggan)
    {
        return $query->where('id_pelanggan', $idPelanggan);
    }
}


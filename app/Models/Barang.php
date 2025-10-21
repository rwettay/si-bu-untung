<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;   // PK string, bukan auto-increment
    protected $keyType = 'string';

    // Kolom yang boleh di-mass assign
    protected $fillable = [
        'id_barang',
        'nama_barang',
        'stok_barang',
        'harga_satuan',
        'tanggal_kedaluwarsa',
        'gambar_url',      // <- disesuaikan dengan migrasi
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'harga_satuan'        => 'decimal:2',
        'stok_barang'         => 'integer',
    ];

    /**
     * Supaya implicit route-model-binding pakai id_barang,
     * contoh: route('barang.edit', $barang) -> /barang/{id_barang}
     */
    public function getRouteKeyName()
    {
        return 'id_barang';
    }

    // Relasi contoh (opsional)
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_barang', 'id_barang');
    }
}

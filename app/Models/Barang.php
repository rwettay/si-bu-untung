<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'stok_barang',
        'harga_satuan',
        'tanggal_kedaluwarsa',
        'gambar',
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'harga_satuan'        => 'decimal:2',
        'stok_barang'         => 'integer',
    ];

    // Relasi contoh (opsional)
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_barang', 'id_barang');
    }
}

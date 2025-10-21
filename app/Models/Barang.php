<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model {
    protected $table = 'barang';
protected $primaryKey = 'id_barang';
public $incrementing = false;
protected $keyType = 'string';
protected $fillable = [
    'id_barang','nama_barang','stok_barang','harga_satuan',
    'tanggal_kedaluwarsa','gambar_url','is_recommended','sold_count'
];


    protected $casts = [
        'stok_barang'=>'integer','harga_satuan'=>'integer',
        'is_recommended'=>'boolean','sold_count'=>'integer',
        'tanggal_kedaluwarsa'=>'date',
    ];

    // Relasi contoh (opsional)
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_barang', 'id_barang');
    }
}

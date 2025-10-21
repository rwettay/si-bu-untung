<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;        // PK string
    protected $keyType = 'string';

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'stok_barang',
        'harga_satuan',
        'tanggal_kedaluwarsa',
        'gambar_url',
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'harga_satuan'        => 'decimal:2',
        'stok_barang'         => 'integer',
    ];

    // Supaya route-model binding pakai id_barang
    public function getRouteKeyName()
    {
        return 'id_barang';
    }

    // ---- Opsional quality-of-life ----

    /** Scope pencarian sederhana: Barang::search($q)->paginate() */
    public function scopeSearch($query, ?string $q)
    {
        return $query->when($q, function ($qq) use ($q) {
            $qq->where('id_barang', 'like', "%{$q}%")
               ->orWhere('nama_barang', 'like', "%{$q}%");
        });
    }

    /** Terima string kosong untuk tanggal -> simpan null (biar aman) */
    public function setTanggalKedaluwarsaAttribute($value)
    {
        $this->attributes['tanggal_kedaluwarsa'] = $value ?: null;
    }

    // Relasi contoh (kalau memang dipakai)
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_barang', 'id_barang');
    }
}

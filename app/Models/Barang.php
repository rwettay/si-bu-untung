<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;          // PK string
    protected $keyType = 'string';

    // Jika tabel TIDAK punya created_at / updated_at, set false:
    // public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'stok_barang',
        'harga_satuan',
        'tanggal_kedaluwarsa',
        'gambar_url',
        'is_recommended',
        'sold_count',
    ];

    protected $casts = [
        'stok_barang'          => 'integer',
        'harga_satuan'         => 'integer',
        'is_recommended'       => 'boolean',
        'sold_count'           => 'integer',
        'tanggal_kedaluwarsa'  => 'date',
    ];

    /** Route-model binding pakai kolom id_barang */
    public function getRouteKeyName(): string
    {
        return 'id_barang';
    }

    /** Scope pencarian sederhana: Barang::search($q)->paginate() */
    public function scopeSearch($query, ?string $q)
    {
        return $query->when($q, function ($qq) use ($q) {
            $qq->where('id_barang', 'like', "%{$q}%")
               ->orWhere('nama_barang', 'like', "%{$q}%");
        });
    }

    /** Terima string kosong untuk tanggal -> simpan null */
    public function setTanggalKedaluwarsaAttribute($value): void
    {
        $this->attributes['tanggal_kedaluwarsa'] = $value ?: null;
    }

    /** Relasi contoh (sesuaikan jika dipakai) */
    public function detailTransaksis()
    {
        return $this->hasMany(\App\Models\DetailTransaksi::class, 'id_barang', 'id_barang');
    }
}

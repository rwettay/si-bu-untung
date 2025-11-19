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

    /**
     * Update is_recommended berdasarkan kriteria otomatis
     * 
     * Kriteria:
     * 1. sold_count >= min_sold_count (default: 20) - top performer
     * 2. stok_barang > min_stock (default: 5) - masih tersedia
     * 3. ATAU: produk baru (created_at < X hari, default: 7 hari) - new arrivals
     * 
     * @param int $minSoldCount Minimum sold_count untuk menjadi recommended (default: 20)
     * @param int $minStock Minimum stok yang harus ada (default: 5)
     * @param int $newProductDays Produk baru dalam X hari terakhir (default: 7)
     * @return array Statistik update: ['updated' => int, 'recommended' => int, 'unrecommended' => int]
     */
    public static function updateRecommendedStatus(
        int $minSoldCount = 20,
        int $minStock = 5,
        int $newProductDays = 7
    ): array {
        // Hitung percentile sold_count untuk threshold dinamis (opsional)
        $allSoldCounts = self::where('sold_count', '>', 0)
            ->orderBy('sold_count', 'desc')
            ->pluck('sold_count')
            ->toArray();
        
        $thresholdSoldCount = $minSoldCount;
        if (count($allSoldCounts) > 0) {
            // Ambil top 30% dari sold_count sebagai threshold
            $top30Index = (int) ceil(count($allSoldCounts) * 0.3);
            if ($top30Index > 0 && $top30Index < count($allSoldCounts)) {
                $thresholdSoldCount = max($minSoldCount, $allSoldCounts[$top30Index - 1]);
            }
        }

        // Tanggal batas untuk "produk baru"
        $newProductDate = now()->subDays($newProductDays);

        // Reset semua recommended dulu
        self::query()->update(['is_recommended' => false]);
        
        // Update menjadi recommended jika memenuhi kriteria:
        // 1. (sold_count >= threshold DAN stok >= minStock) ATAU
        // 2. Produk baru (dibuat dalam X hari terakhir) dengan stok >= minStock
        $recommendedQuery = self::where(function ($query) use ($thresholdSoldCount, $minStock, $newProductDate) {
            $query->where(function ($q) use ($thresholdSoldCount, $minStock) {
                // Kriteria 1: Top performer dengan stok mencukupi
                $q->where('sold_count', '>=', $thresholdSoldCount)
                  ->where('stok_barang', '>=', $minStock);
            })->orWhere(function ($q) use ($newProductDate, $minStock) {
                // Kriteria 2: Produk baru dengan stok mencukupi
                $q->where('created_at', '>=', $newProductDate)
                  ->where('stok_barang', '>=', $minStock);
            });
        });

        $recommendedCount = $recommendedQuery->count();
        $recommendedQuery->update(['is_recommended' => true]);

        // Hitung yang direkomendasikan dan tidak direkomendasikan
        $totalRecommended = self::where('is_recommended', true)->count();
        $totalUnrecommended = self::where('is_recommended', false)->count();

        return [
            'updated' => $recommendedCount,
            'recommended' => $totalRecommended,
            'unrecommended' => $totalUnrecommended,
            'threshold_sold_count' => $thresholdSoldCount,
        ];
    }
}

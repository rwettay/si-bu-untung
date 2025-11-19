<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_transaksi',
        'id_pelanggan',
        'nama_penerima',
        'telepon_penerima',
        'alamat_pengiriman',
        'tanggal_transaksi',
        'tanggal_pengiriman',
        'waktu_pengiriman',
        'total_transaksi',
        'id_staff',
        'status_transaksi',
        'bukti_pengiriman',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total_transaksi'   => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'id_staff', 'id_staff');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}

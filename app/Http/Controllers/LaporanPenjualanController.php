<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $period = strtolower($request->get('period', 'harian')); // harian|mingguan|bulanan|tahunan
        $dateIn = $request->get('date');

        // --- Parse input date sesuai period ---
        try {
            switch ($period) {
                case 'mingguan':
                    // format HTML week: 2025-W46
                    if (is_string($dateIn) && preg_match('/^\d{4}-W\d{2}$/', $dateIn)) {
                        $date = Carbon::createFromFormat('o-\WW', $dateIn)->startOfDay();
                    } else {
                        $date = Carbon::parse($dateIn ?? now())->startOfDay();
                    }
                    $start = $date->copy()->startOfWeek(Carbon::MONDAY);
                    $end   = $date->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
                    break;

                case 'bulanan':
                    // format HTML month: 2025-11
                    if (is_string($dateIn) && preg_match('/^\d{4}-\d{2}$/', $dateIn)) {
                        $date = Carbon::createFromFormat('Y-m', $dateIn)->startOfDay();
                    } else {
                        $date = Carbon::parse($dateIn ?? now())->startOfDay();
                    }
                    $start = $date->copy()->startOfMonth();
                    $end   = $date->copy()->endOfMonth()->endOfDay();
                    break;

                case 'tahunan':
                    // angka tahun: 2025
                    if (is_string($dateIn) && preg_match('/^\d{4}$/', $dateIn)) {
                        $date = Carbon::createFromFormat('Y', $dateIn)->startOfDay();
                    } else {
                        $date = Carbon::parse($dateIn ?? now())->startOfDay();
                    }
                    $start = $date->copy()->startOfYear();
                    $end   = $date->copy()->endOfYear()->endOfDay();
                    break;

                default: // harian
                    $period = 'harian';
                    $date   = Carbon::parse($dateIn ?? now())->startOfDay();
                    $start  = $date->copy()->startOfDay();
                    $end    = $date->copy()->endOfDay();
            }
        } catch (\Throwable $e) {
            // fallback aman
            $period = 'harian';
            $date = now()->startOfDay();
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->endOfDay();
        }

        // --------- Deteksi nama kolom secara fleksibel ----------
        // transaksi: primary key & kolom tanggal
        $tPk = Schema::hasColumn('transaksi', 'id') ? 'id'
             : (Schema::hasColumn('transaksi', 'id_transaksi') ? 'id_transaksi' : 'id');

        $tDateCol = Schema::hasColumn('transaksi', 'tanggal_transaksi') ? 'tanggal_transaksi'
                  : (Schema::hasColumn('transaksi', 'tanggal') ? 'tanggal' : null);

        // detail_transaksi: foreign key ke transaksi, id barang, qty, harga satuan
        $dtTransFk = Schema::hasColumn('detail_transaksi', 'transaksi_id') ? 'transaksi_id'
                    : (Schema::hasColumn('detail_transaksi', 'id_transaksi') ? 'id_transaksi' : 'transaksi_id');

        $dtBarangFk = Schema::hasColumn('detail_transaksi', 'id_barang') ? 'id_barang'
                    : (Schema::hasColumn('detail_transaksi', 'barang_id') ? 'barang_id' : 'id_barang');

        $qtyCol = Schema::hasColumn('detail_transaksi', 'qty') ? 'qty'
                : (Schema::hasColumn('detail_transaksi', 'jumlah_barang') ? 'jumlah_barang'
                : (Schema::hasColumn('detail_transaksi', 'jumlah') ? 'jumlah' : null));

        $hargaCol = Schema::hasColumn('detail_transaksi', 'harga_satuan') ? 'harga_satuan'
                  : (Schema::hasColumn('detail_transaksi', 'harga') ? 'harga'
                  : (Schema::hasColumn('detail_transaksi', 'harga_jual') ? 'harga_jual' : null));

        // barang: nama barang
        $bNamaCol = Schema::hasColumn('barang', 'nama_barang') ? 'nama_barang'
                   : (Schema::hasColumn('barang', 'nama') ? 'nama' : 'nama_barang');

        $rows = [];

        // --------- Query hanya jika kolom kunci tersedia ----------
        if ($tDateCol && $qtyCol && $hargaCol) {
            try {
                $type = Schema::getColumnType('transaksi', $tDateCol); // 'date' | 'datetime' | ...
            } catch (\Throwable $e) {
                $type = 'datetime';
            }

            $startVal = $type === 'date' ? $start->toDateString() : $start->toDateTimeString();
            $endVal   = $type === 'date' ? $end->toDateString()   : $end->toDateTimeString();

            $rows = DB::table('detail_transaksi as dt')
                ->join('transaksi as t', "t.$tPk", '=', "dt.$dtTransFk")
                ->join('barang as b', "b.id_barang", '=', "dt.$dtBarangFk")
                ->whereBetween("t.$tDateCol", [$startVal, $endVal])
                ->selectRaw("
                    DATE(t.$tDateCol) as tanggal,
                    b.$bNamaCol as nama_barang,
                    dt.$qtyCol   as qty,
                    dt.$hargaCol as harga_satuan,
                    (dt.$qtyCol * dt.$hargaCol) as subtotal
                ")
                ->orderBy("t.$tDateCol")
                ->get()
                ->map(fn ($r) => (array) $r)
                ->toArray();
        }

        return view('filament.pages.laporan-penjualan', [
            'rows'   => $rows,
            'period' => $period,
            'date'   => $date, // Carbon instance
        ]);
    }
}


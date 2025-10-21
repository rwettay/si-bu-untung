<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function barang(Request $request)
    {
        $q = $request->query('q');
        $nearDays = 30;

        $barangs = Barang::when($q, fn($qb)=>$qb
                ->where('id_barang','like',"%{$q}%")
                ->orWhere('nama_barang','like',"%{$q}%"))
            ->orderBy('nama_barang')
            ->paginate(10)->withQueryString()
            ->through(function($b) use ($nearDays){
                $status = 'Aman';
                $today = now()->startOfDay();

                if ($b->tanggal_kedaluwarsa) {
                    $exp = Carbon::parse($b->tanggal_kedaluwarsa);
                    if ($exp->lt($today)) {
                        $status = 'Kadaluwarsa';
                    } elseif ($exp->lte($today->copy()->addDays($nearDays))) {
                        $status = 'Hampir Kadaluwarsa';
                    }
                }
                if ($status === 'Aman' && (int)$b->stok_barang <= 10) {
                    $status = 'Hampir Habis';
                }

                $b->status_bar = $status;
                return $b;
            });

        return view('laporan.barang', compact('barangs','q','nearDays'));
    }

    public function penjualan(Request $request)
    {
        $tipe    = $request->query('tipe', 'harian'); // harian|mingguan
        $tanggal = $request->query('tanggal', now()->toDateString());

        $start = Carbon::parse($tanggal)->startOfDay();
        $end   = Carbon::parse($tanggal)->endOfDay();
        if ($tipe === 'mingguan') {
            $start = Carbon::parse($tanggal)->startOfWeek();
            $end   = Carbon::parse($tanggal)->endOfWeek();
        }

        // sesuaikan nama kolom tabel kamu
        $rows = DB::table('detail_transaksi')
            ->selectRaw('DATE(created_at) as tgl,
                         COUNT(*) as jumlah_transaksi,
                         SUM(jumlah) as total_item,
                         SUM(jumlah * harga_satuan) as pendapatan')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();

        $ringkasan = [
            'total_transaksi' => (int) $rows->sum('jumlah_transaksi'),
            'total_item'      => (int) $rows->sum('total_item'),
            'pendapatan'      => (float) $rows->sum('pendapatan'),
        ];

        return view('laporan.penjualan', compact('tipe','tanggal','rows','ringkasan','start','end'));
    }
}

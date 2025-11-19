<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $warnDays = 30;
        $lowStock = 10;
        $today = now()->startOfDay();

        $totalPenjualan = (int) (Transaksi::sum('total_transaksi') ?? 0);
        $totalPengunjung = Pelanggan::count();

        $hampirExpire = Barang::query()
            ->whereNotNull('tanggal_kedaluwarsa')
            ->whereBetween('tanggal_kedaluwarsa', [$today, (clone $today)->addDays($warnDays)])
            ->count();

        $hampirHabis = Barang::query()
            ->whereNotNull('stok_barang')
            ->where('stok_barang', '<=', $lowStock)
            ->count();

        return view('dashboard', compact(
            'totalPenjualan',
            'totalPengunjung',
            'hampirExpire',
            'hampirHabis'
        ));
    }
}


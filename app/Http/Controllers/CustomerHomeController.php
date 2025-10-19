<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class CustomerHomeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));

        // Cari berdasarkan nama_barang saja (karena tidak ada kolom 'nama')
        $base = Barang::query()
            ->when($q, function ($qb) use ($q) {
                $qb->where('nama_barang', 'like', "%{$q}%");
            });

        // PRODUK REKOMENDASI: pakai flag is_recommended + urut terbaru (id_barang desc)
        $recommended = (clone $base)
            ->where('is_recommended', true)
            ->orderBy('id_barang', 'desc')
            ->limit(12)
            ->get();

        // TERLARIS: urutkan berdasarkan sold_count (kalau sama, fallback id_barang desc)
        $bestSellers = (clone $base)
            ->orderByDesc('sold_count')
            ->orderBy('id_barang', 'desc')
            ->limit(12)
            ->get();

        return view('customer.home', compact('q', 'recommended', 'bestSellers'));
    }
}

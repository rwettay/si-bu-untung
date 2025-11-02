<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class CustomerHomeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        // Cari berdasarkan nama_barang saja
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

        // TERLARIS: urutkan berdasarkan sold_count
        $bestSellers = (clone $base)
            ->orderByDesc('sold_count')
            ->orderBy('id_barang', 'desc')
            ->limit(12)
            ->get();

        return view('customer.home', compact('q', 'recommended', 'bestSellers'));
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        // Cek jika query kosong
        if (empty($q)) {
            // Jika query kosong, kembalikan tampilan tanpa hasil
            return view('customer.search', [
                'q' => $q,
                'products' => [],  // Tidak ada produk jika pencarian kosong
            ]);
        }

        // Query barang berdasarkan pencarian, hanya produk yang tersedia
    $products = \App\Models\Barang::search($q)  // Menggunakan scope search
        ->where('stok_barang', '>', 0)  // Hanya yang ada stok
        ->orderBy('nama_barang', 'asc')  // Urutkan berdasarkan nama_barang
        ->get();

        // Kembalikan view dengan data pencarian
        return view('customer.search', [
            'q' => $q,
            'products' => $products,
        ]);
    }
}

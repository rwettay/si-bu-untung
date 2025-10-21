<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function add(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'product_id' => ['required', 'string'],  // Gunakan 'string' karena id_barang bertipe string
            'qty'        => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        // Simpan keranjang sederhana di session
        $cart = session('cart', []);
        $cart[$data['product_id']] = ($cart[$data['product_id']] ?? 0) + $data['qty'];
        session(['cart' => $cart]);

        // Jika request mengharapkan JSON, balas JSON
        if ($r->wantsJson()) {
            return redirect()->back()->with('ok', 'Ditambahkan ke keranjang');
        }

        return redirect()->back()->with('ok', 'Ditambahkan ke keranjang');
    }

    public function index()
    {
        // Mengambil data keranjang dari session
        $cart = session('cart', []);
        
        // Mengambil produk berdasarkan ID yang ada di keranjang
        $products = Barang::whereIn('id_barang', array_keys($cart))->get();

        // Hitung subtotal
        $subtotal = 0;
        foreach ($cart as $productId => $qty) {
            $product = $products->firstWhere('id_barang', $productId);
            $subtotal += $product->harga_satuan * $qty;
        }

        // Total harga (bisa ditambahkan diskon jika ada)
        $total = $subtotal;

        // Tampilkan halaman cart dengan data keranjang dan produk
        return view('customer.cart', compact('cart', 'products', 'subtotal', 'total'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class CartController extends Controller
{
    /**
     * Simpan jumlah item unik ke session untuk badge keranjang.
     */
    private function updateCartBadge(array $cart): void
    {
        // Menghitung jumlah produk unik di keranjang
        $unique = count($cart);
        session(['cart_unique_count' => $unique]);
    }

    /**
     * Tambah item ke keranjang (pakai id_barang & jumlah_pesanan)
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'jumlah_pesanan' => 'required|integer|min:1'
        ]);

        $barang = Barang::where('id_barang', $request->id_barang)->firstOrFail();

        $cart = session('cart', []);

        // PERBAIKAN: Cek apakah item sudah ada di keranjang
        if (isset($cart[$request->id_barang])) {
            // Jika sudah ada, tambahkan jumlah_pesanan (bukan ganti)
            $current = $cart[$request->id_barang];
            $wanted = $current + $request->jumlah_pesanan;
        } else {
            // Jika belum ada, set ke jumlah_pesanan
            $wanted = $request->jumlah_pesanan;
        }

        // Validasi stok
        if ($wanted > $barang->stok_barang) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup. Sisa stok: ' . $barang->stok_barang
            ], 400);
        }

        // Tambahkan item ke keranjang
        $cart[$request->id_barang] = $wanted;
        session(['cart' => $cart]);

        // Update cart badge
        $this->updateCartBadge($cart);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart), // BENAR: Menghitung produk unik
            'message' => 'Produk berhasil ditambahkan ke keranjang'
        ]);
    }

    /**
     * Update jumlah_pesanan untuk 1 item di keranjang
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'jumlah_pesanan' => 'required|integer|min:1'
        ]);

        $cart = session('cart', []);

        if (isset($cart[$request->id_barang])) {
            $barang = Barang::where('id_barang', $request->id_barang)->first();

            // Validasi stok
            if ($barang && $request->jumlah_pesanan > $barang->stok_barang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak cukup. Sisa stok: ' . $barang->stok_barang
                ], 400);
            }

            $cart[$request->id_barang] = $request->jumlah_pesanan;
            session(['cart' => $cart]);

            // Update jumlah barang unik di session untuk badge keranjang
            $this->updateCartBadge($cart);

            // FIX: Mengembalikan jumlah produk unik, bukan quantity item
            return response()->json([
                'success' => true,
                'cart_count' => count($cart), // DIPERBAIKI: Dari $cart[$request->id_barang] ke count($cart)
                'message' => 'Jumlah produk di keranjang diperbarui'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang'
        ], 404);
    }

    /**
     * Tampilkan isi keranjang
     */
    public function index()
    {
        $cart = session('cart', []);
        $this->updateCartBadge($cart);

        $products = empty($cart)
            ? collect()
            : Barang::whereIn('id_barang', array_keys($cart))->get();

        $subtotal = 0;
        foreach ($cart as $id_barang => $jumlah_pesanan) {
            if ($p = $products->firstWhere('id_barang', $id_barang)) {
                $subtotal += $p->harga_satuan * $jumlah_pesanan;
            }
        }
        $total = $subtotal;

        return view('customer.cart', compact('cart', 'products', 'subtotal', 'total'));
    }

    /**
     * Hapus satu item dari keranjang
     */
    public function remove(Request $request, string $id_barang)
    {
        $cart = session('cart', []);

        if (isset($cart[$id_barang])) {
            unset($cart[$id_barang]);
            session(['cart' => $cart]);
            $this->updateCartBadge($cart);

            // FIX: Menambahkan cart_count ke response
            return response()->json([
                'success' => true,
                'cart_count' => count($cart), // DITAMBAHKAN: Untuk update badge
                'message' => 'Produk dihapus dari keranjang'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan di keranjang'
        ], 404);
    }

    /**
     * Kosongkan seluruh keranjang
     */
    public function clear(Request $request)
    {
        session()->forget('cart');
        session(['cart_unique_count' => 0]);

        return response()->json([
            'success' => true,
            'cart_count' => 0, // KONSISTENSI: Menambahkan cart_count
            'message' => 'Keranjang dikosongkan'
        ]);
    }
}

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
     * Tambah item ke keranjang (pakai id_barang & jumlah_pesanan)
     */
    public function add(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'id_barang'       => ['required', 'string'],
            'jumlah_pesanan'  => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $barang = Barang::where('id_barang', $data['id_barang'])->firstOrFail();

        // Keranjang di session: ['ID_BARANG' => jumlah_pesanan]
        $cart = session('cart', []);
        $current = $cart[$data['id_barang']] ?? 0;
        $wanted  = $current + $data['jumlah_pesanan'];

        // Validasi stok terhadap stok_barang
        if ($wanted > $barang->stok_barang) {
            return back()->with('err', 'Stok tidak cukup. Sisa stok: ' . $barang->stok_barang);
        }

        $cart[$data['id_barang']] = $wanted;
        session(['cart' => $cart]);

        return back()->with('ok', 'Produk ditambahkan ke keranjang');
    }

    /**
     * Tampilkan isi keranjang
     */
    public function index()
    {
        $cart = session('cart', []); // ['ID_BARANG' => jumlah_pesanan]
        $products = empty($cart)
            ? collect()
            : Barang::whereIn('id_barang', array_keys($cart))->get();

        $subtotal = 0;
        foreach ($cart as $id_barang => $jumlah_pesanan) {
            if ($p = $products->firstWhere('id_barang', $id_barang)) {
                $subtotal += $p->harga_satuan * $jumlah_pesanan;
            }
        }
        $total = $subtotal; // tempatkan logika diskon/ongkir jika ada

        return view('customer.cart', compact('cart', 'products', 'subtotal', 'total'));
    }

    /**
     * Update jumlah_pesanan untuk 1 item di keranjang
     */
    public function update(Request $r)
    {
        $data = $r->validate([
            'id_barang' => ['required', 'string'],
            'jumlah_pesanan' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $cart = session('cart', []);
        
        // Jika barang ditemukan di keranjang
        if (isset($cart[$data['id_barang']])) {
            $barang = Barang::where('id_barang', $data['id_barang'])->first();
            
            // Validasi stok
            if ($barang && $data['jumlah_pesanan'] > $barang->stok_barang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak cukup. Sisa stok: ' . $barang->stok_barang
                ], 400);
            }

            $cart[$data['id_barang']] = $data['jumlah_pesanan'];
            session(['cart' => $cart]);

            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan di keranjang.'
        ], 404);
    }

    /**
     * Hapus satu item dari keranjang
     * Support both AJAX and regular request
     */
    public function remove(Request $r, string $id_barang)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$id_barang])) {
            unset($cart[$id_barang]);
            session(['cart' => $cart]);
            
            // Jika request AJAX, return JSON
            if ($r->expectsJson() || $r->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk dihapus dari keranjang'
                ]);
            }
            
            // Jika request biasa, redirect
            return redirect()->route('cart.index')->with('ok', 'Produk dihapus dari keranjang');
        }

        // Jika barang tidak ditemukan
        if ($r->expectsJson() || $r->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan di keranjang'
            ], 404);
        }

        return redirect()->route('cart.index')->with('err', 'Produk tidak ditemukan di keranjang');
    }

    /**
     * Kosongkan seluruh keranjang
     */
    public function clear(Request $r)
    {
        session()->forget('cart');
        
        // Support AJAX request
        if ($r->expectsJson() || $r->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang dikosongkan'
            ]);
        }
        
        return redirect()->route('cart.index')->with('ok', 'Keranjang dikosongkan');
    }

    /**
     * Checkout — simpan ke transaksi & detail_transaksi, kurangi stok_barang
     */
    public function checkout(Request $r): RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('err', 'Keranjang masih kosong!');
        }

        try {
            DB::transaction(function () use ($cart) {
                // ID transaksi sederhana (silakan ganti ke generator berurut jika perlu)
                $idTransaksi = 'TRX' . now()->format('YmdHis');

                $trx = Transaksi::create([
                    'id_transaksi'      => $idTransaksi,
                    'id_pelanggan'      => auth()->user()->id_pelanggan ?? null,
                    'total_transaksi'   => 0,
                    'tanggal_transaksi' => now()->toDateString(),
                    'id_staff'          => auth()->user()->id_staff ?? null,
                    'status_transaksi'  => 'pending',
                ]);

                $total = 0;

                foreach ($cart as $id_barang => $jumlah_pesanan) {
                    $barang = Barang::where('id_barang', $id_barang)
                                    ->lockForUpdate()
                                    ->firstOrFail();

                    if ($jumlah_pesanan > $barang->stok_barang) {
                        throw new \Exception("Stok tidak cukup untuk {$barang->nama_barang}");
                    }

                    $subtotal = $jumlah_pesanan * $barang->harga_satuan;

                    DetailTransaksi::create([
                        'id_transaksi'   => $idTransaksi,
                        'id_barang'      => $id_barang,
                        'jumlah_pesanan' => $jumlah_pesanan,
                        'subtotal'       => $subtotal,
                    ]);

                    // Kurangi stok
                    $barang->decrement('stok_barang', $jumlah_pesanan);
                    $total += $subtotal;
                }

                // finalisasi transaksi
                $trx->update([
                    'total_transaksi'  => $total,
                    'status_transaksi' => 'dibayar', // sesuaikan alur pembayaranmu
                ]);

                // kosongkan keranjang
                session()->forget('cart');
            });

            return redirect()->route('cart.index')->with('ok', 'Checkout berhasil!');
            
        } catch (\Exception $e) {
            return back()->with('err', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
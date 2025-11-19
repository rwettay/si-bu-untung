<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerTransaksiController extends Controller
{
    /**
     * Display a listing of the transactions for the authenticated customer.
     */
    public function index(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        $transaksis = Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with(['detailTransaksis.barang'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.transaksi', compact('transaksis'));
    }

    /**
     * Display the specified transaction.
     */
    public function show($id_transaksi)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        $transaksi = Transaksi::where('id_transaksi', $id_transaksi)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->with(['detailTransaksis.barang'])
            ->firstOrFail();

        return view('customer.transaksi-detail', compact('transaksi'));
    }
}


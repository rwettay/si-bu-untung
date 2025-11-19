<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengantaranController extends Controller
{
    /**
     * Display list of transactions ready for delivery
     */
    public function index(Request $request)
    {
        $query = Transaksi::query()
            ->with(['pelanggan', 'staff', 'detailTransaksis.barang'])
            ->whereIn('status_transaksi', ['dibayar', 'dalam_pengiriman']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('id_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_penerima', 'like', "%{$search}%")
                  ->orWhere('alamat_pengiriman', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($q) use ($search) {
                      $q->where('nama_pelanggan', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'tanggal_transaksi');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $transaksis = $query->paginate(10)->withQueryString();

        return view('pengantaran.index', compact('transaksis'));
    }

    /**
     * Take order for delivery
     */
    public function ambilPesanan(Request $request, $id_transaksi)
    {
        $staff = Auth::guard('staff')->user();
        
        if (!$staff instanceof Staff) {
            return redirect()->route('pengantaran.index')
                ->with('error', 'Anda harus login sebagai staff.');
        }

        $transaksi = Transaksi::findOrFail($id_transaksi);

        if ($transaksi->status_transaksi !== 'dibayar') {
            return redirect()->route('pengantaran.index')
                ->with('error', 'Hanya pesanan dengan status "Dibayar" yang bisa diambil.');
        }

        $transaksi->update([
            'id_staff' => $staff->id_staff,
            'status_transaksi' => 'dalam_pengiriman',
        ]);

        return redirect()->route('pengantaran.index')
            ->with('success', 'Pesanan berhasil diambil. Status telah diubah menjadi "Dalam Pengiriman".');
    }

    /**
     * Upload proof of delivery
     */
    public function uploadBukti(Request $request, $id_transaksi)
    {
        $staff = Auth::guard('staff')->user();
        
        if (!$staff instanceof Staff) {
            return redirect()->route('pengantaran.index')
                ->with('error', 'Anda harus login sebagai staff.');
        }

        $validated = $request->validate([
            'bukti_pengiriman' => ['required', 'image', 'max:5120'], // 5MB max
        ], [
            'bukti_pengiriman.required' => 'Foto bukti pengiriman wajib diunggah.',
            'bukti_pengiriman.image' => 'File harus berupa gambar.',
            'bukti_pengiriman.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $transaksi = Transaksi::findOrFail($id_transaksi);

        // Check if transaction belongs to this staff
        if ($transaksi->status_transaksi !== 'dalam_pengiriman' || 
            $transaksi->id_staff !== $staff->id_staff) {
            return redirect()->route('pengantaran.index')
                ->with('error', 'Anda tidak memiliki izin untuk upload bukti pesanan ini.');
        }

        // Upload file
        $path = $request->file('bukti_pengiriman')->store('bukti-pengiriman', 'public');

        $transaksi->update([
            'bukti_pengiriman' => $path,
            'status_transaksi' => 'terkirim',
        ]);

        return redirect()->route('pengantaran.index')
            ->with('success', 'Bukti pengiriman berhasil diupload. Status pesanan telah diubah menjadi "Terkirim".');
    }
}


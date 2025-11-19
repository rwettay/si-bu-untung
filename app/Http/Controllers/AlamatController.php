<?php

namespace App\Http\Controllers;

use App\Models\AlamatPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlamatController extends Controller
{
    /**
     * Ambil semua alamat pelanggan yang sedang login
     */
    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $alamat = AlamatPelanggan::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $alamat
        ]);
    }

    /**
     * Simpan alamat baru
     */
    public function store(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'nama_penerima' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'alamat_lengkap' => 'required|string',
            'catatan' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Jika is_default = true, set semua alamat lain menjadi false
            if ($validated['is_default'] ?? false) {
                AlamatPelanggan::where('id_pelanggan', $pelanggan->id_pelanggan)
                    ->update(['is_default' => false]);
            }

            $alamat = AlamatPelanggan::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'label' => $validated['label'] ?? 'Alamat',
                'nama_penerima' => $validated['nama_penerima'],
                'telepon' => $validated['telepon'],
                'alamat_lengkap' => $validated['alamat_lengkap'],
                'catatan' => $validated['catatan'] ?? null,
                'lat' => $validated['lat'] ?? null,
                'lng' => $validated['lng'] ?? null,
                'is_default' => $validated['is_default'] ?? false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil ditambahkan',
                'data' => $alamat
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update alamat
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $alamat = AlamatPelanggan::where('id', $id)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->first();

        if (!$alamat) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'nama_penerima' => 'sometimes|required|string|max:100',
            'telepon' => 'sometimes|required|string|max:15',
            'alamat_lengkap' => 'sometimes|required|string',
            'catatan' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Jika is_default = true, set semua alamat lain menjadi false
            if (isset($validated['is_default']) && $validated['is_default']) {
                AlamatPelanggan::where('id_pelanggan', $pelanggan->id_pelanggan)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            // Update hanya field yang ada di request
            $alamat->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil diupdate',
                'data' => $alamat->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus alamat
     */
    public function destroy($id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $alamat = AlamatPelanggan::where('id', $id)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->first();

        if (!$alamat) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan'
            ], 404);
        }

        // Validasi: minimal harus ada 1 alamat
        $totalAlamat = AlamatPelanggan::where('id_pelanggan', $pelanggan->id_pelanggan)->count();
        
        if ($totalAlamat <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus memiliki minimal 1 alamat. Tidak dapat menghapus alamat terakhir.'
            ], 400);
        }

        $alamat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus'
        ]);
    }
}


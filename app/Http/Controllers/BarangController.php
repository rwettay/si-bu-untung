<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function create()
    {
        // pastikan SELALU ada $barangs
        $barangs = Barang::orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

        return view('barang.tambah', compact('barangs')); // <- WAJIB 'barang.tambah'
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang'   => ['required','string','max:20'],
            'stok_barang' => ['required','integer','min:1'],
        ]);

        $barang = Barang::where('id_barang', $data['id_barang'])->first();

        if ($barang) {
            $barang->increment('stok_barang', (int)$data['stok_barang']);
        } else {
            Barang::create([
                'id_barang'           => $data['id_barang'],
                'stok_barang'         => (int)$data['stok_barang'],
                // kolom lain biarkan default/null
            ]);
        }

        return back()->with('success', 'Barang berhasil ditambahkan / stok diperbarui.');
    }
// Halaman EDIT (list + form kilat)
public function editPage(\Illuminate\Http\Request $request)
{
    $q = $request->query('q');
    $barangs = \App\Models\Barang::when($q, fn($qb)=>$qb
            ->where('id_barang','like',"%{$q}%")
            ->orWhere('nama_barang','like',"%{$q}%"))
        ->orderBy('nama_barang')
        ->paginate(8)
        ->withQueryString();

    return view('barang.edit', compact('barangs','q'));
}

// Halaman HAPUS
public function deletePage(\Illuminate\Http\Request $request)
{
    $q = $request->query('q');
    $barangs = \App\Models\Barang::when($q, fn($qb)=>$qb
            ->where('id_barang','like',"%{$q}%")
            ->orWhere('nama_barang','like',"%{$q}%"))
        ->orderBy('nama_barang')
        ->paginate(8)
        ->withQueryString();

    return view('barang.hapus', compact('barangs','q'));
}

// Quick update (ubah salah satu kolom)
public function quickUpdate(\Illuminate\Http\Request $request)
{
    $mode = $request->input('mode');           // 'change_id' | 'change_nama' | 'change_tanggal' | 'change_stok'
    $id   = $request->input('target_id');      // id barang yang mau diubah

    $barang = \App\Models\Barang::find($id);
    if (!$barang) {
        return back()->withErrors(['target_id' => 'Barang dengan ID tersebut tidak ditemukan.'])->withInput();
    }

    switch ($mode) {
        case 'change_id':
            $request->validate([
                'new_id' => ['required','string','max:20','unique:barang,id_barang'],
            ]);
            // ubah PK
            $barang->id_barang = $request->new_id;
            $barang->save();
            return back()->with('success', "ID barang diubah menjadi {$request->new_id}.");

        case 'change_nama':
            $request->validate(['nama_barang' => ['required','string']]);
            $barang->nama_barang = $request->nama_barang;
            $barang->save();
            return back()->with('success','Nama barang diperbarui.');

        case 'change_tanggal':
            $request->validate(['tanggal_kedaluwarsa' => ['nullable','date']]);
            $barang->tanggal_kedaluwarsa = $request->tanggal_kedaluwarsa ?: null;
            $barang->save();
            return back()->with('success','Tanggal kedaluwarsa diperbarui.');

        case 'change_stok':
            $request->validate(['stok_barang' => ['required','integer','min:0']]);
            $barang->stok_barang = (int)$request->stok_barang;
            $barang->save();
            return back()->with('success','Stok barang diperbarui.');

        default:
            return back()->withErrors(['mode' => 'Aksi tidak dikenal.']);
    }
}

// Hapus
public function destroy(\App\Models\Barang $barang)
{
    $barang->delete();
    return back()->with('success','Barang berhasil dihapus.');
}

    // index/edit/update/destroy biarkan seperti sebelumnya…
}

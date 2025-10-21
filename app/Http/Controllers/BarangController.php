<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // List + cari
    public function index(Request $request)
    {
        $q = $request->query('q');

        $barangs = Barang::when($q, function ($qb) use ($q) {
                $qb->where('id_barang', 'like', "%{$q}%")
                   ->orWhere('nama_barang', 'like', "%{$q}%");
            })
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

        return view('barang.index', compact('barangs', 'q'));
    }

    // Form tambah
    public function create()
    {
        return view('barang.form', ['barang' => new Barang()]);
    }

    // Simpan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang'            => ['required','string','max:20','unique:barang,id_barang'],
            'nama_barang'          => ['required','string'],
            'stok_barang'          => ['required','integer','min:0'],
            'harga_satuan'         => ['required','numeric','min:0'],
            'gambar_url'           => ['nullable','string'],
            'tanggal_kedaluwarsa'  => ['nullable','date'],
        ]);

        Barang::create($data);
        return redirect()->route('barang.index')->with('success','Barang ditambahkan.');
    }

    // Form edit
    public function edit(Barang $barang)
    {
        return view('barang.form', compact('barang'));
    }

    // Update
    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'id_barang'            => ['required','string','max:20', Rule::unique('barang','id_barang')->ignore($barang->id_barang, 'id_barang')],
            'nama_barang'          => ['required','string'],
            'stok_barang'          => ['required','integer','min:0'],
            'harga_satuan'         => ['required','numeric','min:0'],
            'gambar_url'           => ['nullable','string'],
            'tanggal_kedaluwarsa'  => ['nullable','date'],
        ]);

        // Kalau id_barang boleh diubah, update PK juga:
        $pkChanged = $data['id_barang'] !== $barang->id_barang;

        $barang->update($data);

        // Jika PK berubah, redirect pakai key baru
        return redirect()->route('barang.index')->with('success','Barang diperbarui.');
    }

    // Hapus
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success','Barang dihapus.');
    }
}

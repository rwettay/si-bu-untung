<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // ====== RESOURCE CRUD ======

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

    public function create()
    {
        return view('barang.form', ['barang' => new Barang()]);
    }

    public function store(Request $request)
    {
        // Alur form lengkap (resource) – stok & harga tetap wajib
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

    public function edit(Barang $barang)
    {
        return view('barang.form', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        // Alur form lengkap (resource)
        $data = $request->validate([
            'id_barang'            => ['required','string','max:20', Rule::unique('barang','id_barang')->ignore($barang->id_barang, 'id_barang')],
            'nama_barang'          => ['required','string'],
            'stok_barang'          => ['required','integer','min:0'],
            'harga_satuan'         => ['required','numeric','min:0'],
            'gambar_url'           => ['nullable','string'],
            'tanggal_kedaluwarsa'  => ['nullable','date'],
        ]);

        $barang->update($data);
        return redirect()->route('barang.index')->with('success','Barang diperbarui.');
    }

    public function destroy(Request $request, Barang $barang)
    {
        $barang->delete();

        // Default balik ke halaman hapus (UI mockup), bisa override via input hidden redirect_to
        $to = $request->input('redirect_to') ?: route('ui.hapus');
        return redirect($to)->with('success','Barang dihapus.');
    }

    // ====== QUICK PAGES (mockup sidebar) ======

    /** UI Edit (search + kartu mini + tabel) */
    public function quickEditPage(Request $request)
    {
        $q          = $request->query('q');
        $selectedId = $request->query('id');

        $barangs = Barang::when($q, function ($qb) use ($q) {
                $qb->where('id_barang', 'like', "%{$q}%")
                   ->orWhere('nama_barang', 'like', "%{$q}%");
            })
            ->orderBy('id_barang')
            ->paginate(8)
            ->withQueryString();

        return view('barang.edit', compact('barangs', 'q', 'selectedId'));
    }

    /** Quick insert dari halaman /tambah (form cepat/minimal) */
    public function quickStore(Request $request)
    {
        // HANYA id & nama yang wajib; lainnya opsional
        $data = $request->validate([
            'id_barang'            => ['required','string','max:20','unique:barang,id_barang'],
            'nama_barang'          => ['required','string'],
            'stok_barang'          => ['nullable','integer','min:0'],
            'harga_satuan'         => ['nullable','numeric','min:0'],
            'gambar_url'           => ['nullable','string'],
            'tanggal_kedaluwarsa'  => ['nullable','date'],
        ]);

        // Beri default aman untuk kolom numerik NOT NULL
        $data['stok_barang']  = $data['stok_barang']  ?? 0;
        $data['harga_satuan'] = $data['harga_satuan'] ?? 0;

        Barang::create($data);

        // Kembali ke halaman tambah (mockup) dengan pesan sukses
        return redirect()->route('ui.tambah')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Quick update satu field dari kartu mini (edit-page).
     * Form mengirim: id_barang, type (nama|tanggal|stok), value
     */
    public function quickUpdate(Request $request)
    {
        $request->validate([
            'id_barang' => ['required','exists:barang,id_barang'],
            'type'      => ['required','in:nama,tanggal,stok'],
        ]);

        $barang = Barang::where('id_barang', $request->id_barang)->firstOrFail();

        switch ($request->type) {
            case 'nama':
                $request->validate(['value' => ['required','string']]);
                $barang->nama_barang = $request->value;
                $barang->save();
                $msg = 'Nama barang diperbarui.';
                break;

            case 'tanggal':
                if ($request->filled('value')) {
                    $request->validate(['value' => ['date']]);
                    $barang->tanggal_kedaluwarsa = $request->value; // format Y-m-d dari input type=date
                } else {
                    $barang->tanggal_kedaluwarsa = null;
                }
                $barang->save();
                $msg = 'Tanggal kedaluwarsa diperbarui.';
                break;

            case 'stok':
                $request->validate(['value' => ['required','integer','min:0']]);
                $barang->stok_barang = (int) $request->value;
                $barang->save();
                $msg = 'Stok barang diperbarui.';
                break;

            default:
                $msg = 'Tidak ada perubahan.';
        }

        return back()
            ->withInput($request->only('q','id_barang','type','value'))
            ->with('success', $msg);
    }
}

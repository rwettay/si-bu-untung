<x-app-layout>
  <x-slot name="header">
    <h2 class="font-extrabold text-xl">
      {{ $barang->exists ? 'Edit Barang' : 'Tambah Barang' }}
    </h2>
  </x-slot>

  <div class="bg-white rounded-xl shadow border p-6 max-w-xl">
    <form method="post"
          action="{{ $barang->exists ? route('barang.update', $barang) : route('barang.store') }}">
      @csrf
      @if($barang->exists) @method('PUT') @endif

      <div class="mb-3">
        <label class="block text-sm font-medium mb-1">ID Barang</label>
        <input name="id_barang" value="{{ old('id_barang', $barang->id_barang) }}"
               class="w-full border rounded px-3 py-2" {{ $barang->exists ? '' : 'required' }}>
        @error('id_barang') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium mb-1">Nama Barang</label>
        <input name="nama_barang" required value="{{ old('nama_barang', $barang->nama_barang) }}"
               class="w-full border rounded px-3 py-2">
        @error('nama_barang') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3 grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm font-medium mb-1">Stok</label>
          <input type="number" name="stok_barang" min="0"
                 value="{{ old('stok_barang', $barang->stok_barang ?? 0) }}"
                 class="w-full border rounded px-3 py-2">
          @error('stok_barang') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Harga Satuan</label>
          <input type="number" step="0.01" min="0" name="harga_satuan"
                 value="{{ old('harga_satuan', $barang->harga_satuan ?? 0) }}"
                 class="w-full border rounded px-3 py-2">
          @error('harga_satuan') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="block text-sm font-medium mb-1">Tanggal Kadaluwarsa</label>
        <input type="date" name="tanggal_kedaluwarsa"
               value="{{ old('tanggal_kedaluwarsa', optional($barang->tanggal_kedaluwarsa)->format('Y-m-d')) }}"
               class="w-full border rounded px-3 py-2">
        @error('tanggal_kedaluwarsa') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">URL Gambar (opsional)</label>
        <input type="text" name="gambar_url" value="{{ old('gambar_url', $barang->gambar_url) }}"
               class="w-full border rounded px-3 py-2">
        @error('gambar_url') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
      </div>

      <div class="flex gap-2">
        <button class="px-4 py-2 rounded bg-emerald-600 text-white">
          {{ $barang->exists ? 'Update' : 'Simpan' }}
        </button>
        <a href="{{ route('barang.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
      </div>
    </form>
  </div>
</x-app-layout>

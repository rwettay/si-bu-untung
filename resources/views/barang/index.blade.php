<x-app-layout>
  <x-slot name="header">
    <h2 class="font-extrabold text-xl">Data Barang</h2>
  </x-slot>

  <div class="bg-white rounded-xl shadow border p-4">
    @if(session('success'))
      <div class="mb-3 px-3 py-2 rounded bg-emerald-50 text-emerald-700 text-sm">
        {{ session('success') }}
      </div>
    @endif

    <form method="get" class="mb-3 flex gap-2">
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari id/nama..."
             class="border rounded px-3 py-2 w-64">
      <a href="{{ route('barang.create') }}" class="px-3 py-2 rounded bg-emerald-600 text-white">Tambah</a>
    </form>

    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="text-left p-2">ID</th>
            <th class="text-left p-2">Nama</th>
            <th class="text-left p-2">Kedaluwarsa</th>
            <th class="text-left p-2">Stok</th>
            <th class="text-left p-2">Harga</th>
            <th class="text-left p-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($barangs as $b)
            <tr class="border-t">
              <td class="p-2">{{ $b->id_barang }}</td>
              <td class="p-2">{{ $b->nama_barang }}</td>
              <td class="p-2">{{ optional($b->tanggal_kedaluwarsa)->format('d/m/Y') ?? '-' }}</td>
              <td class="p-2">{{ $b->stok_barang }}</td>
              <td class="p-2">Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
              <td class="p-2">
                <a href="{{ route('barang.edit', $b) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('barang.destroy', $b) }}" method="post" class="inline"
                      onsubmit="return confirm('Hapus barang ini?')">
                  @csrf @method('DELETE')
                  <button class="text-red-600 ml-2">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td class="p-3" colspan="6">Tidak ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $barangs->links() }}
    </div>
  </div>
</x-app-layout>

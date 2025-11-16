{{-- resources/views/barang/tambah-page.blade.php --}}
<x-app-layout>
  <x-slot name="header"><span class="font-extrabold text-xl">Owner</span></x-slot>

  <style>
    .h1-title{font-weight:800;font-size:32px;line-height:1.2;text-align:center;margin:0 0 18px}
    .search-wrap{display:flex;justify-content:center;margin-bottom:22px}
    .search{position:relative;width:100%;max-width:560px}
    .search input{width:100%;height:40px;border:1px solid #e5e7eb;border-radius:999px;padding:0 14px 0 36px;background:#fff;font:500 14px/40px 'Poppins',system-ui}
    .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af}

    .mini{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06);padding:16px;margin-bottom:22px}
    .mini .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .mini label{font:600 12px 'Poppins',system-ui;color:#374151}
    .mini input{width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font:500 13px/20px 'Poppins',system-ui}
    .btn{border:0;background:#111;color:#fff;border-radius:10px;padding:10px 14px;font:600 13px 'Poppins',system-ui;cursor:pointer}
    .err{margin-top:6px;color:#b91c1c;font:600 12px 'Poppins',system-ui}

    .tbl-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06)}
    .tbl-title{padding:16px 20px;font:600 14px 'Poppins',system-ui}
    table{width:100%;border-collapse:collapse;font:500 13px 'Poppins',system-ui}
    thead th{background:#f3f4f6;text-align:left;padding:10px 14px;border-bottom:1px solid #e5e7eb}
    tbody td{padding:10px 14px;border-top:1px solid #f0f0f0}
    tbody tr:nth-child(odd){background:#fcfcfc}

    .pagination{display:flex;justify-content:center;gap:6px;padding:12px 16px}
    .page-btn{min-width:28px;height:28px;padding:0 8px;border:1px solid #e5e7eb;background:#fff;border-radius:6px;font:600 12px/28px 'Poppins',system-ui;text-align:center;color:#111}
    .page-btn.is-active{background:#2563eb;color:#fff;border-color:#2563eb}
    .page-btn.icon{font-weight:600}
    .page-container{max-width:1100px;margin:0 auto}
    .alert{margin:10px 0 18px;padding:10px 12px;border-radius:10px;font:600 13px 'Poppins',system-ui}
    .alert-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
    .alert-error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
  </style>

  <div class="page-container">
    <h1 class="h1-title">Cari barang yang ingin di tambah</h1>

    {{-- Flash --}}
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())     <div class="alert alert-error">{{ $errors->first() }}</div>   @endif

    {{-- Search (opsional) --}}
    <div class="search-wrap">
      <form class="search" method="get" action="{{ url()->current() }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
          <path d="M15.5 14h-.79l-.28-.27A6.5 6.5 0 1 0 14 15.5l.27.28h.79l5 5 1.5-1.5-5-5ZM10 15.5A5.5 5.5 0 1 1 10 4.5a5.5 5.5 0 0 1 0 11Z"/>
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Barang...">
      </form>
    </div>

    {{-- FORM TAMBAH CEPAT: HANYA ID & NAMA --}}
    <div class="mini">
      <form method="post" action="{{ route('barang.quick.store') }}">
        @csrf
        <div class="row">
          <div>
            <label>Id Barang</label>
            <input type="text"
                   name="id_barang"
                   value="{{ old('id_barang', request('prefill_id')) }}"
                   placeholder="Contoh: rkk999" required>
            @error('id_barang') <div class="err">{{ $message }}</div> @enderror
          </div>

          <div>
            <label>Nama Barang</label>
            <input type="text"
                   name="nama_barang"
                   value="{{ old('nama_barang') }}"
                   placeholder="Nama barang" required>
            @error('nama_barang') <div class="err">{{ $message }}</div> @enderror
          </div>
        </div>

        <div style="margin-top:12px;display:flex;gap:10px;justify-content:flex-end">
          <button type="submit" class="btn">Tambah Barang</button>
          {{-- Tombol "Form Lengkap" dihapus --}}
        </div>
      </form>
    </div>

    {{-- Tabel + pagination (opsional) --}}
    <div class="tbl-card">
      <div class="tbl-title">Table Barang</div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th style="width:120px">Id Barang</th>
              <th>Nama Barang</th>
              <th style="width:120px">Stok Barang</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($barangs ?? []) as $b)
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->stok_barang }}</td>
              </tr>
            @empty
              <tr><td colspan="3" style="text-align:center;color:#6b7280;padding:16px">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @isset($barangs)
        @php $current=$barangs->currentPage(); $last=$barangs->lastPage(); @endphp
        <div class="pagination">
          <a class="page-btn icon" href="{{ $barangs->previousPageUrl() ?? '#' }}">«</a>
          @for($i=max(1,$current-2); $i<=min($last,$current+2); $i++)
            <a class="page-btn {{ $i==$current?'is-active':'' }}" href="{{ $barangs->url($i) }}">{{ $i }}</a>
          @endfor
          <a class="page-btn icon" href="{{ $barangs->nextPageUrl() ?? '#' }}">»</a>
        </div>
      @endisset
    </div>
  </div>
</x-app-layout>

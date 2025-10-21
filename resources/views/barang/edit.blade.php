<x-app-layout>
  <x-slot name="header"><span class="font-extrabold text-xl">Owner</span></x-slot>

  @php
    // menjaga state ketika kembali dari validasi
    $q          = old('q', $q ?? request('q'));
    $selectedId = old('id_barang', $selectedId ?? request('id'));
    $lastType   = old('type'); // untuk tahu kartu mana yang terakhir disubmit
  @endphp

  <style>
    .h1-title{font-weight:800;font-size:32px;line-height:1.2;text-align:center;margin:0 0 18px}
    .search-wrap{display:flex;justify-content:center;margin-bottom:22px}
    .search{position:relative;width:100%;max-width:560px}
    .search input{width:100%;height:40px;border:1px solid #e5e7eb;border-radius:999px;padding:0 14px 0 36px;background:#fff;font:500 14px/40px 'Poppins',system-ui}
    .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af}

    .grid-2{display:grid;grid-template-columns:1fr;gap:18px;margin-bottom:22px}
    @media(min-width:860px){.grid-2{grid-template-columns:1fr 1fr}}

    .mini{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06);padding:14px}
    .mini .label{font:600 13px/1.4 'Poppins',system-ui;color:#374151;margin-bottom:8px}
    .mini .row{display:flex;gap:10px}
    .mini input[type="text"], .mini input[type="date"], .mini input[type="number"]{flex:1;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font:500 13px/20px 'Poppins',system-ui}
    .btn-ghost{border:0;background:#f3f4f6;border-radius:10px;padding:10px 14px;font:600 12px 'Poppins',system-ui;color:#111;cursor:pointer}
    .btn-ghost[disabled]{opacity:.6;cursor:not-allowed}
    .hint{display:flex;align-items:center;gap:6px;margin-top:8px;font:500 11px/1.4 'Poppins',system-ui;color:#9ca3af}
    .dot{width:8px;height:8px;border-radius:50%;background:#d1d5db;display:inline-block}

    .alert{margin:10px 0 18px;padding:10px 12px;border-radius:10px;font:600 13px 'Poppins',system-ui}
    .alert-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
    .alert-error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}

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
    .err{margin-top:8px;color:#b91c1c;font:600 12px 'Poppins',system-ui}
  </style>

  <div class="page-container">
    <h1 class="h1-title">Cari barang yang ingin di edit</h1>

    {{-- Flash --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any() && !$lastType)
      {{-- fallback bila ada error umum --}}
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- Search --}}
    <div class="search-wrap">
      <form class="search" method="get" action="{{ route('ui.edit') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
          <path d="M15.5 14h-.79l-.28-.27A6.5 6.5 0 1 0 14 15.5l.27.28h.79l5 5 1.5-1.5-5-5ZM10 15.5A5.5 5.5 0 1 1 10 4.5a5.5 5.5 0 0 1 0 11Z"/>
        </svg>
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari Barang...">
        @if(!empty($selectedId))
          <input type="hidden" name="id" value="{{ $selectedId }}">
        @endif
      </form>
    </div>

    {{-- 4 kartu mini --}}
    <div class="grid-2">
      {{-- Pilih ID --}}
      <div class="mini">
        <div class="label">Id Barang</div>
        <form class="row" method="get" action="{{ route('ui.edit') }}">
          <input type="text" name="id" value="{{ $selectedId }}" placeholder="Masukan Id Barang">
          @if(!empty($q)) <input type="hidden" name="q" value="{{ $q }}"> @endif
          <button type="submit" class="btn-ghost">Edit</button>
        </form>
        <div class="hint"><span class="dot"></span>Masukan Id Barang yang ingin di edit</div>
      </div>

      {{-- Edit Tanggal --}}
      <div class="mini">
        <div class="label">Tanggal Kadaluwarsa</div>
        <form class="row" method="post" action="{{ route('barang.quick.update') }}">
          @csrf
          <input type="hidden" name="type" value="tanggal">
          <input type="hidden" name="id_barang" value="{{ $selectedId }}">
          <input type="date" name="value" value="{{ $lastType==='tanggal' ? old('value') : '' }}">
          <button type="submit" class="btn-ghost" {{ empty($selectedId) ? 'disabled' : '' }}>Edit</button>
        </form>
        @if($lastType==='tanggal' && $errors->has('value'))
          <div class="err">{{ $errors->first('value') }}</div>
        @endif
        <div class="hint"><span class="dot"></span>Pilih Tanggal Kadaluwarsa yang ingin di edit</div>
      </div>

      {{-- Edit Nama --}}
      <div class="mini">
        <div class="label">Nama Barang</div>
        <form class="row" method="post" action="{{ route('barang.quick.update') }}">
          @csrf
          <input type="hidden" name="type" value="nama">
          <input type="hidden" name="id_barang" value="{{ $selectedId }}">
          <input type="text" name="value" placeholder="Masukan Nama Barang" value="{{ $lastType==='nama' ? old('value') : '' }}">
          <button type="submit" class="btn-ghost" {{ empty($selectedId) ? 'disabled' : '' }}>Edit</button>
        </form>
        @if($lastType==='nama' && $errors->has('value'))
          <div class="err">{{ $errors->first('value') }}</div>
        @endif
        <div class="hint"><span class="dot"></span>Masukan Nama Barang yang ingin di edit</div>
      </div>

      {{-- Edit Stok --}}
      <div class="mini">
        <div class="label">Stok Barang</div>
        <form class="row" method="post" action="{{ route('barang.quick.update') }}">
          @csrf
          <input type="hidden" name="type" value="stok">
          <input type="hidden" name="id_barang" value="{{ $selectedId }}">
          <input type="number" min="0" name="value" placeholder="Masukan Stok Barang" value="{{ $lastType==='stok' ? old('value') : '' }}">
          <button type="submit" class="btn-ghost" {{ empty($selectedId) ? 'disabled' : '' }}>Edit</button>
        </form>
        @if($lastType==='stok' && $errors->has('value'))
          <div class="err">{{ $errors->first('value') }}</div>
        @endif
        <div class="hint"><span class="dot"></span>Masukan Stok Barang yang ingin di edit</div>
      </div>
    </div>

    {{-- Tabel + pagination --}}
    <div class="tbl-card">
      <div class="tbl-title">Table Barang</div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th>Id Barang</th>
              <th>Nama Barang</th>
              <th>Tanggal Kadaluwarsa</th>
              <th>Stok Barang</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($barangs ?? []) as $b)
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ optional($b->tanggal_kedaluwarsa)->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $b->stok_barang }}</td>
              </tr>
            @empty
              <tr><td colspan="4" style="text-align:center;color:#6b7280;padding:16px">Tidak ada data.</td></tr>
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

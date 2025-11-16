<x-app-layout>
  <x-slot name="header"><span class="font-extrabold text-xl">Owner</span></x-slot>

  <style>
    .h1-title{font-weight:800;font-size:32px;line-height:1.2;text-align:center;margin:0 0 18px}
    .search-wrap{display:flex;justify-content:center;margin-bottom:22px}
    .search{position:relative;width:100%;max-width:560px}
    .search input{
      width:100%;height:40px;border:1px solid #e5e7eb;border-radius:999px;
      padding:0 14px 0 36px;background:#fff;font:500 14px/40px 'Poppins',system-ui
    }
    .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af}

    .tbl-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06)}
    .tbl-title{padding:16px 20px;font:600 14px 'Poppins',system-ui}
    table{width:100%;border-collapse:collapse;font:500 13px 'Poppins',system-ui}
    thead th{background:#f3f4f6;text-align:left;padding:10px 14px;border-bottom:1px solid #e5e7eb}
    tbody td{padding:10px 14px;border-top:1px solid #f0f0f0}
    tbody tr:nth-child(odd){background:#fcfcfc}

    .btn-del{
      width:26px;height:26px;border-radius:999px;border:0;background:#3b82f6;
      display:inline-grid;place-items:center;color:#fff;cursor:pointer
    }
    .btn-del:hover{filter:brightness(.95)}
    .btn-del svg{width:14px;height:14px}

    .pagination{display:flex;justify-content:center;gap:6px;padding:12px 16px}
    .page-btn{
      min-width:28px;height:28px;padding:0 8px;border:1px solid #e5e7eb;background:#fff;
      border-radius:6px;font:600 12px/28px 'Poppins',system-ui;text-align:center;color:#111
    }
    .page-btn.is-active{background:#2563eb;color:#fff;border-color:#2563eb}
    .page-btn.icon{font-weight:600}

    .alert{margin:10px 0 18px;padding:10px 12px;border-radius:10px;font:600 13px 'Poppins',system-ui}
    .alert-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}

    .page-container{max-width:1100px;margin:0 auto}
  </style>

  <div class="page-container">
    <h1 class="h1-title">Cari barang yang ingin di hapus</h1>

    {{-- Flash sukses --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="search-wrap">
      <form class="search" method="get" action="{{ route('ui.hapus') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
          <path d="M15.5 14h-.79l-.28-.27A6.5 6.5 0 1 0 14 15.5l.27.28h.79l5 5 1.5-1.5-5-5ZM10 15.5A5.5 5.5 0 1 1 10 4.5a5.5 5.5 0 0 1 0 11Z"/>
        </svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Barang...">
      </form>
    </div>

    {{-- Tabel + pagination --}}
    <div class="tbl-card">
      <div class="tbl-title">Table Barang</div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th style="width:120px">Id Barang</th>
              <th>Nama Barang</th>
              <th style="width:200px">Tanggal Kadaluwarsa</th>
              <th style="width:120px">Stok Barang</th>
              <th style="width:70px;text-align:center"> </th>
            </tr>
          </thead>
          <tbody>
            @forelse(($barangs ?? []) as $b)
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ optional($b->tanggal_kedaluwarsa)->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $b->stok_barang }}</td>
                <td style="text-align:center">
                  <form method="POST"
                        action="{{ route('barang.destroy', $b) }}"
                        onsubmit="return confirm('Hapus barang {{ $b->nama_barang }} ({{ $b->id_barang }})?')">
                    @csrf
                    @method('DELETE')
                    {{-- Pastikan kembali ke halaman & query yang sama setelah hapus --}}
                    <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                    <button type="submit" class="btn-del" title="Hapus" aria-label="Hapus {{ $b->nama_barang }}">
                      <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M9 3h6l1 2h5v2H3V5h5l1-2Zm1 6h2v9h-2V9Zm4 0h2v9h-2V9ZM7 9h2v9H7V9Z"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align:center;color:#6b7280;padding:16px">
                  Tidak ada data.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @isset($barangs)
        @php
          $current = $barangs->currentPage();
          $last    = $barangs->lastPage();
        @endphp
        <div class="pagination">
          <a class="page-btn icon" href="{{ $barangs->previousPageUrl() ?? '#' }}">«</a>
          @for($i = max(1, $current-2); $i <= min($last, $current+2); $i++)
            <a class="page-btn {{ $i==$current ? 'is-active' : '' }}"
               href="{{ $barangs->url($i) }}">{{ $i }}</a>
          @endfor
          <a class="page-btn icon" href="{{ $barangs->nextPageUrl() ?? '#' }}">»</a>
        </div>
      @endisset
    </div>
  </div>
</x-app-layout>

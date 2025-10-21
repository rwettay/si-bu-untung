{{-- resources/views/barang/hapus.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hapus Barang</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>/* mirip edit, ringkas */ 
    :root{--sidebar:#181818;--sidebar-hover:#232323;--content-bg:#f5f5f5;--card-bg:#fff;--text:#111;--radius:14px;--shadow:0 10px 25px rgba(0,0,0,.06)}
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;background:var(--content-bg);color:var(--text)}
    .layout{display:grid;grid-template-columns:260px 1fr;min-height:100vh}
    .sidebar{background:var(--sidebar);color:#fff;position:sticky;top:0;height:100vh;padding:16px 0}
    .logo{display:flex;gap:10px;align-items:center;padding:0 18px 18px;margin-bottom:8px;border-bottom:1px solid rgba(255,255,255,.06)}
    .logo .dot{width:12px;height:12px;border-radius:4px;background:#f59e0b}
    .menu{list-style:none;margin:0;padding:8px 10px}.menu>li{margin:4px 0}
    .menu a{display:flex;align-items:center;gap:10px;text-decoration:none;color:#ddd;padding:12px 16px;border-radius:10px}
    .menu a:hover,.menu a.active{background:var(--sidebar-hover);color:#fff}
    .content{padding:28px 34px}.title{font-size:28px;font-weight:800}.divider{height:1px;background:#e8e8e8;margin:10px 0 24px}
    .heading{font-size:26px;font-weight:800;text-align:center;margin:0 0 12px}
    .searchWrap{max-width:560px;margin:0 auto 16px;position:relative}
    .search{width:100%;height:40px;border-radius:24px;border:1px solid #e3e3e3;background:#f2f2f2;padding:0 14px 0 36px}
    .searchIcon{position:absolute;left:12px;top:0;height:40px;display:grid;place-items:center;color:#9aa0a6}
    .card{background:var(--card-bg);border-radius:var(--radius);box-shadow:var(--shadow);padding:18px}
    .tableWrap{overflow:auto;border:1px solid #e6e6e6;border-radius:12px}
    table{width:100%;border-collapse:collapse;font-size:13px}
    thead{background:#e9e9e9} th,td{padding:10px 14px;text-align:left;border-top:1px solid #efefef}
    tbody tr:nth-child(even){background:#fafafa} tbody tr:hover{background:#f3f4f6}
    .trash{display:grid;place-items:center;width:26px;height:26px;border-radius:50%;border:1px solid #e5e7eb;background:#fff;cursor:pointer}
    .trash:hover{background:#fee2e2;border-color:#fecaca}
    .flash{background:#ecfeff;color:#155e75;border:1px solid #a5f3fc;padding:10px;border-radius:10px;margin:12px auto;max-width:560px;text-align:center}
    .pagination{display:flex;gap:6px;justify-content:center;margin-top:12px}
  </style>
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div class="logo"><span class="dot"></span><strong>SI Bu Untung</strong></div>
    <ul class="menu">
      <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
      <li class="open">
        <a href="javascript:void(0)" onclick="this.parentElement.classList.toggle('open')">Kelola Barang
          <svg width="16" height="16" viewBox="0 0 24 24" fill="#bbb"><path d="M7 10l5 5 5-5z"/></svg>
        </a>
        <div class="submenu">
          <a href="{{ url('/tambah') }}">Tambah Barang</a>
          <a href="{{ url('/edit') }}">Edit Barang</a>
          <a href="{{ url('/hapus') }}" style="color:#fff">Hapus Barang</a>
        </div>
      </li>
      <li><a href="{{ url('/laporan/penjualan') }}">Laporan Penjualan</a></li>
    </ul>
  </aside>

  <main class="content">
    <div class="title">Owner</div>
    <div class="divider"></div>

    <h1 class="heading">Cari barang yang ingin di hapus</h1>

    <div class="searchWrap">
      <span class="searchIcon"><svg width="18" height="18" viewBox="0 0 24 24" fill="#9aa0a6"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79L20 21.49 21.49 20l-5.99-6zM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg></span>
      <input id="searchBarang" class="search" placeholder="Cari Barang...">
    </div>

    @if(session('success')) <div class="flash">{{ session('success') }}</div> @endif

    <div class="card">
      <h3 style="margin:0 0 10px;font-weight:600">Table Barang</h3>
      <div class="tableWrap">
        <table>
          <thead><tr><th>Id Barang</th><th>Nama Barang</th><th>Tanggal Kadaluwarsa</th><th>Stok Barang</th><th style="width:56px">Aksi</th></tr></thead>
          <tbody id="tableBarang">
            @forelse($barangs as $b)
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ optional($b->tanggal_kedaluwarsa)->format('d/m/Y') }}</td>
                <td>{{ $b->stok_barang }}</td>
                <td>
                  <form method="POST" action="{{ route('barang.destroy', $b->id_barang) }}"
                        onsubmit="return confirm('Hapus barang {{ $b->nama_barang }}?')">
                    @csrf @method('DELETE')
                    <button class="trash" title="Hapus">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="#ef4444">
                        <path d="M6 7h12v13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7zm3-4h6l1 1h4v2H4V4h4l1-1z"/>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" style="text-align:center;padding:18px;color:#6b7280">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="pagination">{{ $barangs->onEachSide(1)->links() }}</div>
    </div>
  </main>
</div>

<script>
  document.getElementById('searchBarang')?.addEventListener('keyup', function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tableBarang tr').forEach(row=>{
      const nama = (row.cells?.[1]?.textContent || '').toLowerCase();
      row.style.display = nama.includes(q) ? '' : 'none';
    });
  });
</script>
</body>
</html>

{{-- resources/views/barang/tambah.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Barang</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --sidebar:#181818;      /* warna sidebar (hitam keabu) */
      --sidebar-hover:#232323;
      --content-bg:#f5f5f5;   /* latar konten */
      --card-bg:#ffffff;      /* latar kartu */
      --text:#111;
      --muted:#777;
      --accent:#f0592b;       /* oranye */
      --green:#1f9d55;        /* hijau */
      --yellow:#f59e0b;       /* kuning */
      --radius:14px;
      --shadow:0 10px 25px rgba(0,0,0,.06);
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{margin:0; font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial; color:var(--text); background:var(--content-bg)}
    .layout{display:grid; grid-template-columns:260px 1fr; min-height:100vh}

    /* SIDEBAR (copy persis dari dashboard) */
    .sidebar{background:var(--sidebar); color:#fff; position:sticky; top:0; height:100vh; padding:16px 0}
    .logo{display:flex; align-items:center; gap:10px; padding:0 18px 18px; margin-bottom:8px; border-bottom:1px solid rgba(255,255,255,.06)}
    .logo .dot{width:12px; height:12px; border-radius:4px; background:var(--accent)}
    .menu{list-style:none; margin:0; padding:8px 10px}
    .menu > li{margin:4px 0}
    .menu a{display:flex; align-items:center; gap:10px; text-decoration:none; color:#ddd; padding:12px 16px; border-radius:10px}
    .menu a:hover,.menu a.active{background:var(--sidebar-hover); color:#fff}
    .menu .caret{margin-left:auto; transition:.2s}
    .submenu{display:none; padding-left:42px}
    .submenu a{padding:10px 12px; font-size:.92rem; color:#cfcfcf}
    .submenu a:hover{color:#fff}
    .open .submenu{display:block}
    .open .caret{transform:rotate(180deg)}

    /* CONTENT */
    .content{padding:28px 34px}
    .topbar{display:flex; align-items:center; justify-content:space-between; margin-bottom:18px}
    .title{font-size:28px; font-weight:800; letter-spacing:.3px}
    .user-btn{width:40px; height:40px; border-radius:50%; display:grid; place-items:center; background:#111; color:#fff}
    .divider{height:1px; background:#e8e8e8; margin:10px 0 24px}

    /* CARDS */
    .card{background:var(--card-bg); border-radius:var(--radius); box-shadow:var(--shadow); padding:22px;}

    /* FORM AREA */
    .heading{font-size:34px; font-weight:800; text-align:center; margin:2px 0 14px}
    .search-wrap{max-width:560px; margin:0 auto 16px; position:relative}
    .search-input{width:100%; height:44px; border-radius:28px; border:1px solid #e2e2e2; background:#f0f0f0; padding:0 16px 0 40px; outline:none}
    .search-icon{position:absolute; left:14px; top:0; height:44px; display:grid; place-items:center; color:#9aa0a6}

    .grid2{display:grid; grid-template-columns:1fr; gap:18px}
    @media(min-width:900px){ .grid2{grid-template-columns:1fr 1fr} }

    .field-label{font-size:12px; font-weight:600; margin-bottom:6px}
    .input{width:100%; height:38px; border:1px solid #dcdcdc; border-radius:8px; padding:0 12px; outline:none}
    .hint{margin-top:6px; font-size:11px; color:#6b7280; display:flex; align-items:center; gap:6px}
    .btn{height:38px; padding:0 14px; border-radius:8px; background:#e5e7eb; color:#111; border:none; font-weight:600; cursor:pointer}
    .btn:hover{background:#dcdfe4}

    /* TABLE */
    .table-wrap{overflow:auto; border:1px solid #e6e6e6; border-radius:12px}
    table{width:100%; border-collapse:collapse; font-size:13px}
    thead{background:#e9e9e9; color:#111}
    th,td{padding:10px 14px; text-align:left; border-top:1px solid #efefef}
    tbody tr:nth-child(even){background:#fafafa}
    tbody tr:hover{background:#f3f4f6}

    /* Pagination */
    .pagination{display:flex; gap:6px; justify-content:center; margin-top:14px; font-size:13px}
    .pagination svg{height:1em}
  </style>
</head>
<body>

<div class="layout">
  <!-- SIDEBAR (copy persis) -->
  <aside class="sidebar">
    <div class="logo">
      <span class="dot"></span>
      <strong>SI Bu Untung</strong>
    </div>

    <ul class="menu">
      <li><a href="{{ url('/dashboard') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 3H3v7h7V3Zm11 0h-7v7h7V3ZM10 14H3v7h7v-7Zm11 0h-7v7h7v-7Z"/></svg>
        Dashboard
      </a></li>

      <li class="has-sub open">
        <a href="javascript:void(0)" onclick="toggleSub(this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 6H4V4h16v2Zm0 5H4V9h16v2Zm0 5H4v-2h16v2Z"/></svg>
          Kelola Barang
          <svg class="caret" width="16" height="16" viewBox="0 0 24 24" fill="#bbb"><path d="M7 10l5 5 5-5z"/></svg>
        </a>
        <div class="submenu">
          <a href="{{ url('/tambah') }}" style="color:#fff">Tambah Barang</a>
          <a href="{{ url('/edit') }}">Edit Barang</a>
          <a href="{{ url('/hapus') }}">Hapus Barang</a>
        </div>
      </li>

      <li class="has-sub">
        <a href="javascript:void(0)" onclick="toggleSub(this)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/></svg>
          Laporan Barang
          <svg class="caret" width="16" height="16" viewBox="0 0 24 24" fill="#bbb"><path d="M7 10l5 5 5-5z"/></svg>
        </a>
        <div class="submenu">
          <a href="{{ url('/laporan/stok') }}">Stok</a>
          <a href="{{ url('/laporan/kadaluwarsa') }}">Kadaluwarsa</a>
        </div>
      </li>

      <li>
        <a href="{{ url('/laporan/penjualan') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h2v18H3V3Zm16 0h2v18h-2V3ZM9 7h6v2H9V7Zm0 4h8v2H9v-2Zm0 4h5v2H9v-2Z"/></svg>
          Laporan Penjualan
        </a>
      </li>
    </ul>
  </aside>

  <!-- CONTENT -->
  <main class="content">
    <div class="topbar">
      <div class="title">Owner</div>
      <a class="user-btn" title="Akun">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff">
          <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12Zm0 2.4c-3.3 0-9.9 1.7-9.9 5v1.9h19.8v-2c0-3.2-6.6-4.9-9.9-4.9Z"/>
        </svg>
      </a>
    </div>

    <div class="divider"></div>

    {{-- Judul + search --}}
    <h1 class="heading">Cari barang yang ingin di tambah</h1>
    <div class="search-wrap">
      <span class="search-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="#9aa0a6">
          <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79L20 21.49 21.49 20l-5.99-6zM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
        </svg>
      </span>
      <input id="searchBarang" class="search-input" placeholder="Cari Barang...">
    </div>

    {{-- Kartu form dua kolom --}}
    <div class="card" style="margin-top:10px">
      <form action="{{ route('barang.store') }}" method="POST">
        @csrf
        <div class="grid2">
          <div>
            <label class="field-label">Id Barang</label>
            <div style="display:flex; gap:10px">
              <input class="input" name="id_barang" placeholder="Masukan Id Barang" required>
              <button class="btn" type="submit">Tambah</button>
            </div>
            <div class="hint">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="#6b7280"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm.75 15h-1.5v-6h1.5v6zm0-8h-1.5V7h1.5v2z"/></svg>
              Masukan Id Barang yang ingin di tambah
            </div>
            @error('id_barang') <div style="color:#dc2626; font-size:13px; margin-top:6px">{{ $message }}</div> @enderror
          </div>

          <div>
            <label class="field-label">Stok Barang</label>
            <div style="display:flex; gap:10px">
              <input class="input" type="number" min="1" name="stok_barang" placeholder="Masukan Stok Barang" required>
              <button class="btn" type="submit">Tambah</button>
            </div>
            <div class="hint">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="#6b7280"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm.75 15h-1.5v-6h1.5v6zm0-8h-1.5V7h1.5v2z"/></svg>
              Masukan Stok Barang yang ingin di tambah
            </div>
            @error('stok_barang') <div style="color:#dc2626; font-size:13px; margin-top:6px">{{ $message }}</div> @enderror
          </div>
        </div>
      </form>
    </div>

    {{-- Kartu tabel --}}
    <div class="card" style="margin-top:18px">
      <h3 style="font-size:15px; font-weight:600; margin:0 0 12px">Table Barang</h3>

      <div class="table-wrap">
        <table>
          <thead>
            <tr class="uppercase">
              <th>Id Barang</th>
              <th>Nama Barang</th>
              <th>Tanggal Kadaluwarsa</th>
              <th>Stok Barang</th>
            </tr>
          </thead>
          <tbody id="tableBarang">
            @forelse ($barangs ?? [] as $b)
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ optional($b->tanggal_kadaluwarsa)->format('d/m/Y') }}</td>
                <td>{{ $b->stok_barang }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align:center; padding:22px; color:#6b7280">Belum ada data barang.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(isset($barangs) && method_exists($barangs, 'links'))
        <div class="pagination">
          {{ $barangs->onEachSide(1)->links() }}
        </div>
      @endif
    </div>
  </main>
</div>

<script>
  function toggleSub(el){ el.parentElement.classList.toggle('open'); }

  // filter cepat berdasarkan "Nama Barang"
  document.getElementById("searchBarang")?.addEventListener("keyup", function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll("#tableBarang tr").forEach(row => {
      const nama = (row.cells?.[1]?.textContent || '').toLowerCase();
      row.style.display = nama.includes(q) ? '' : 'none';
    });
  });
</script>
</body>
</html>


{{-- resources/views/laporan/penjualan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Penjualan</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
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
    .submenu{display:none;padding-left:42px}.open .submenu{display:block}
    .submenu a{padding:10px 12px;font-size:.92rem;color:#cfcfcf}.submenu a:hover{color:#fff}

    .content{padding:28px 34px}.title{font-size:28px;font-weight:800}.divider{height:1px;background:#e8e8e8;margin:10px 0 24px}
    .card{background:var(--card-bg);border-radius:var(--radius);box-shadow:var(--shadow);padding:18px;margin-bottom:16px}
    .controls{display:flex;gap:14px;flex-wrap:wrap;align-items:center}
    select,input[type="date"]{height:38px;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:0 12px}
    .btn{height:38px;border:none;border-radius:10px;padding:0 16px;background:#2563eb;color:#fff;font-weight:600;cursor:pointer}
    .tableWrap{overflow:auto;border:1px solid #e6e6e6;border-radius:12px}
    table{width:100%;border-collapse:collapse;font-size:13px}
    thead{background:#e9e9e9} th,td{padding:10px 14px;text-align:left;border-top:1px solid #efefef}
    tbody tr:nth-child(even){background:#fafafa} tbody tr:hover{background:#f3f4f6}
    .stats{display:grid;grid-template-columns:repeat(3, minmax(180px,1fr));gap:12px}
    .stat{background:#fff;border:1px solid #eee;border-radius:14px;padding:14px;text-align:center}
    .stat h4{margin:0 0 4px;font-weight:600;color:#6b7280}.stat .v{font-weight:800;font-size:18px}
    .heading{font-size:22px;font-weight:800;margin:0 0 12px}
    .muted{color:#6b7280;font-size:13px}
  </style>
</head>
<body>
<div class="layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo"><span class="dot"></span><strong>SI Bu Untung</strong></div>
    <ul class="menu">
      <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
      <li class="open">
        <a href="javascript:void(0)" onclick="this.parentElement.classList.toggle('open')">Kelola Barang
          <svg class="caret" width="16" height="16" viewBox="0 0 24 24" fill="#bbb"><path d="M7 10l5 5 5-5z"/></svg>
        </a>
        <div class="submenu">
          <a href="{{ url('/tambah') }}">Tambah Barang</a>
          <a href="{{ url('/edit') }}">Edit Barang</a>
          <a href="{{ url('/hapus') }}">Hapus Barang</a>
        </div>
      </li>
      <li><a href="{{ route('laporan.barang') }}">Laporan Barang</a></li>
      <li><a href="{{ route('laporan.penjualan') }}" class="active">Laporan Penjualan</a></li>
    </ul>
  </aside>

  <!-- CONTENT -->
  <main class="content">
    <div class="title">Owner</div>
    <div class="divider"></div>

    <div class="card">
      <div class="heading">Laporan Penjualan</div>
      <form class="controls" method="get">
        <div>
          <label class="muted">Periode Laporan:</label><br>
          <select name="tipe">
            <option value="harian"   {{ $tipe==='harian'?'selected':'' }}>Harian</option>
            <option value="mingguan" {{ $tipe==='mingguan'?'selected':'' }}>Mingguan</option>
          </select>
        </div>
        <div>
          <label class="muted">Pilih Tanggal (Harian/Mingguan):</label><br>
          <input type="date" name="tanggal" value="{{ $tanggal }}">
        </div>
        <div style="align-self:flex-end">
          <button class="btn">Tampilkan</button>
        </div>
      </form>
    </div>

    <div class="card">
      <div class="stats">
        <div class="stat">
          <h4>Total Transaksi</h4>
          <div class="v">{{ number_format($ringkasan['total_transaksi']) }}</div>
        </div>
        <div class="stat">
          <h4>Total Item</h4>
          <div class="v">{{ number_format($ringkasan['total_item']) }}</div>
        </div>
        <div class="stat">
          <h4>Pendapatan</h4>
          <div class="v">Rp {{ number_format($ringkasan['pendapatan'],0,',','.') }}</div>
        </div>
      </div>
      <p class="muted" style="margin-top:10px">
        Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
        – {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}
      </p>
    </div>

    <div class="card">
      <div class="heading">Rincian</div>
      <div class="tableWrap">
        <table>
          <thead>
          <tr>
            <th>Tanggal</th>
            <th>Jumlah Transaksi</th>
            <th>Total Item</th>
            <th>Pendapatan</th>
          </tr>
          </thead>
          <tbody>
          @forelse ($rows as $r)
            <tr>
              <td>{{ \Carbon\Carbon::parse($r->tgl)->format('d/m/Y') }}</td>
              <td>{{ number_format($r->jumlah_transaksi) }}</td>
              <td>{{ number_format($r->total_item) }}</td>
              <td>Rp {{ number_format($r->pendapatan,0,',','.') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="text-align:center;padding:18px;color:#6b7280">
                Silakan pilih periode untuk menampilkan laporan penjualan.
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
</body>
</html>

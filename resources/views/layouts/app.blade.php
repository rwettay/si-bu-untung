{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --sidebar:#181818; --sidebar-hover:#232323; --text:#111; --muted:#777;
      --card-bg:#fff; --content-bg:#f5f5f5; --radius:14px; --shadow:0 10px 25px rgba(0,0,0,.06);
    }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:'Poppins',system-ui,Segoe UI,Roboto,Helvetica,Arial;background:var(--content-bg);color:var(--text)}
    .layout{display:grid;grid-template-columns:260px 1fr;min-height:100vh}
    /* Sidebar */
    .sidebar{background:var(--sidebar);color:#fff;position:sticky;top:0;height:100vh;padding:16px 0}
    .logo{display:flex;align-items:center;gap:10px;padding:0 18px 18px;margin-bottom:8px;border-bottom:1px solid rgba(255,255,255,.06)}
    .logo .dot{width:12px;height:12px;border-radius:4px;background:#f0592b}
    .menu{list-style:none;margin:0;padding:8px 10px}
    .menu > li{margin:4px 0}
    .menu a{display:flex;align-items:center;gap:10px;text-decoration:none;color:#ddd;padding:12px 16px;border-radius:10px}
    .menu a:hover,.menu a.active{background:var(--sidebar-hover);color:#fff}
    .caret{margin-left:auto;color:#bbb}
    /* Use details/summary for submenu (no JS) */
    details{border-radius:10px}
    details > summary{list-style:none;cursor:pointer;display:flex;align-items:center;gap:10px;padding:12px 16px;color:#ddd;border-radius:10px}
    details[open] > summary{background:var(--sidebar-hover);color:#fff}
    details .submenu{padding:6px 0 8px 44px}
    .submenu a{display:block;padding:8px 0;color:#cfcfcf;text-decoration:none}
    .submenu a:hover{color:#fff}
    /* Content */
    .content{padding:28px 34px}
  </style>
</head>
<body>
<div class="layout">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo"><span class="dot"></span><strong>SI Bu Untung</strong></div>

    <ul class="menu">
      <li>
        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 3H3v7h7V3Zm11 0h-7v7h7V3ZM10 14H3v7h7v-7Zm11 0h-7v7h7v-7Z"/></svg>
          Dashboard
        </a>
      </li>

      <li>
        <details {{ request()->is('tambah') || request()->is('edit') || request()->is('hapus') ? 'open' : '' }}>
          <summary>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20 6H4V4h16v2Zm0 5H4V9h16v2Zm0 5H4v-2h16v2Z"/></svg>
            Kelola Barang
            <svg class="caret" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
          </summary>
          <div class="submenu">
            <a href="{{ url('/tambah') }}">Tambah Barang</a>
            <a href="{{ url('/edit') }}">Edit Barang</a>
            <a href="{{ url('/hapus') }}">Hapus Barang</a>
          </div>
        </details>
      </li>

      <li>
        <details>
          <summary>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/></svg>
            Laporan Barang
            <svg class="caret" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
          </summary>
          <div class="submenu">
            <a href="{{ url('/laporan/stok') }}">Stok</a>
            <a href="{{ url('/laporan/kadaluwarsa') }}">Kadaluwarsa</a>
          </div>
        </details>
      </li>

      <li>
        <a href="{{ url('/laporan/penjualan') }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h2v18H3V3Zm16 0h2v18h-2V3ZM9 7h6v2H9V7Zm0 4h8v2H9v-2Zm0 4h5v2H9v-2Z"/></svg>
          Laporan Penjualan
        </a>
      </li>
    </ul>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="content">
    {{-- header slot di kiri atas (Owner) --}}
    @isset($header)
      <div class="mb-3 text-xl font-semibold">{{ $header }}</div>
    @endisset

    {{ $slot }}
  </main>
</div>
</body>
</html>

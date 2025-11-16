{{-- resources/views/barang/tambah.blade.php --}}
<x-app-layout>
  <style>
    .h1-title{font-weight:800;font-size:32px;line-height:1.2;text-align:center;margin:0 0 18px;animation:fadeInDown 0.6s ease-out}

    .mini{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06);padding:16px;margin-bottom:22px;animation:slideInUp 0.5s ease-out}
    .mini .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .mini label{font:600 12px 'Poppins',system-ui;color:#374151}
    .mini input,.mini select{width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px;font:500 13px/20px 'Poppins',system-ui;background:#fff;transition:all 0.3s ease}
    .mini input:focus,.mini select:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,0.1);transform:translateY(-1px)}
    .mini input:hover,.mini select:hover{border-color:#9ca3af}
    .btn{border:0;background:#111;color:#fff;border-radius:10px;padding:10px 14px;font:600 13px 'Poppins',system-ui;cursor:pointer;transition:all 0.3s ease;position:relative;overflow:hidden}
    .btn:hover{background:#2563eb;transform:translateY(-2px);box-shadow:0 4px 12px rgba(37,99,235,0.3)}
    .btn:active{transform:translateY(0);box-shadow:0 2px 6px rgba(37,99,235,0.2)}
    .btn::before{content:'';position:absolute;top:50%;left:50%;width:0;height:0;border-radius:50%;background:rgba(255,255,255,0.3);transform:translate(-50%,-50%);transition:width 0.6s,height 0.6s}
    .btn:hover::before{width:300px;height:300px}
    .err{margin-top:6px;color:#b91c1c;font:600 12px 'Poppins',system-ui;animation:shake 0.4s ease}

    .tbl-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.06);animation:fadeIn 0.6s ease-out 0.2s both}
    .tbl-title{padding:16px 20px;font:600 14px 'Poppins',system-ui}
    table{width:100%;border-collapse:collapse;font:500 13px 'Poppins',system-ui}
    thead th{background:#f3f4f6;text-align:left;padding:10px 14px;border-bottom:1px solid #e5e7eb}
    tbody td{padding:10px 14px;border-top:1px solid #f0f0f0}
    tbody tr:nth-child(odd){background:#fcfcfc}
    tbody tr{transition:all 0.2s ease}
    tbody tr:hover{background:#f0f9ff;transform:scale(1.01);box-shadow:0 2px 8px rgba(0,0,0,0.05)}

    .pagination{display:flex;justify-content:center;gap:6px;padding:12px 16px}
    .page-btn{min-width:28px;height:28px;padding:0 8px;border:1px solid #e5e7eb;background:#fff;border-radius:6px;font:600 12px/28px 'Poppins',system-ui;text-align:center;color:#111;transition:all 0.2s ease;text-decoration:none;display:inline-block}
    .page-btn:hover{background:#e5e7eb;transform:translateY(-2px);box-shadow:0 2px 4px rgba(0,0,0,0.1)}
    .page-btn.is-active{background:#2563eb;color:#fff;border-color:#2563eb}
    .page-btn.icon{font-weight:600}
    .page-container{max-width:1100px;margin:0 auto}
    .alert{margin:10px 0 18px;padding:10px 12px;border-radius:10px;font:600 13px 'Poppins',system-ui;animation:slideInRight 0.5s ease-out}
    .alert-success{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0}
    .alert-error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}

    .img-thumb{width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;background:#f9fafb;transition:transform 0.3s ease}
    .img-thumb:hover{transform:scale(1.1);box-shadow:0 4px 8px rgba(0,0,0,0.15)}
    .badge-exp{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;padding:2px 8px;border-radius:999px;font-weight:700;font-size:12px;margin-left:6px;animation:pulse 2s infinite}
    .badge-soon{background:#fffbeb;color:#92400e;border:1px solid #fde68a;padding:2px 8px;border-radius:999px;font-weight:700;font-size:12px;margin-left:6px}
    .muted{color:#6b7280}

    @keyframes fadeInDown{from{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}
    @keyframes slideInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
    @keyframes fadeIn{from{opacity:0}to{opacity:1}}
    @keyframes slideInRight{from{opacity:0;transform:translateX(-20px)}to{opacity:1;transform:translateX(0)}}
    @keyframes shake{0%,100%{transform:translateX(0)}10%,30%,50%,70%,90%{transform:translateX(-5px)}20%,40%,60%,80%{transform:translateX(5px)}}
    @keyframes pulse{0%,100%{opacity:1}50%{opacity:0.7}}
  </style>

  <div class="page-container">
    <h1 class="h1-title">Cari barang yang ingin di tambah</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())     <div class="alert alert-error">{{ $errors->first() }}</div>   @endif

    {{-- FORM TAMBAH --}}
    <div class="mini">
      <form method="post" action="{{ route('barang.quick.store') }}">
        @csrf

        <div class="row">
          {{-- KATEGORI (menentukan prefix) --}}
          <div>
            <label>Kategori</label>
            <select name="kategori" id="kategori" required>
              @php $firstCode = null; @endphp
              @foreach(($prefixMap ?? []) as $label => $code)
                @php if($firstCode===null) $firstCode = $code; @endphp
                <option value="{{ $label }}" {{ old('kategori')===$label ? 'selected' : '' }}>
                  {{ ucfirst($label) }} ({{ strtoupper($code) }})
                </option>
              @endforeach
            </select>
            @error('kategori') <div class="err">{{ $message }}</div> @enderror
          </div>

          {{-- NAMA BARANG --}}
          <div>
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Nama barang" required>
            @error('nama_barang') <div class="err">{{ $message }}</div> @enderror
          </div>

          {{-- GAMBAR (URL penuh) --}}
          <div style="grid-column: 1 / -1;">
            <label>Gambar (URL)</label>
            <input type="url" name="gambar_url" value="{{ old('gambar_url') }}" placeholder="https://contoh.com/foto.jpg">
            @error('gambar_url') <div class="err">{{ $message }}</div> @enderror
          </div>

{{-- STOK BARANG --}}
<div>
  <label>Stok</label>
  <input
    type="number"
    name="stok_barang"
    value="{{ old('stok_barang') }}"
    min="0"
    step="1"
    placeholder="0">
  @error('stok_barang') <div class="err">{{ $message }}</div> @enderror
</div>

{{-- HARGA SATUAN (Rp) --}}
<div>
  <label>Harga Satuan (Rp)</label>
  <input
    type="number"
    name="harga_satuan"
    value="{{ old('harga_satuan') }}"
    min="0"
    step="1"
    placeholder="15000">
  @error('harga_satuan') <div class="err">{{ $message }}</div> @enderror
</div>



          {{-- TANGGAL KEDALUWARSA --}}
          <div>
            <label>Tanggal Kedaluwarsa</label>
            <input type="date" name="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa') }}">
            @error('tanggal_kedaluwarsa') <div class="err">{{ $message }}</div> @enderror
          </div>
        </div>

        <div style="margin-top:12px;display:flex;gap:10px;justify-content:flex-end">
          <button type="submit" class="btn">Tambah Barang</button>
        </div>
      </form>
    </div>

    {{-- TABEL --}}
    <div class="tbl-card">
      <div class="tbl-title">Table Barang</div>
      <div style="overflow-x:auto">
        <table>
          <thead>
            <tr>
              <th style="width:120px">Id Barang</th>
              <th>Nama Barang</th>
              <th style="width:140px">Harga Satuan</th>
              <th style="width:120px">Gambar</th>
              <th style="width:150px">Tanggal Kadaluwarsa</th>
              <th style="width:90px">Stok Barang</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($barangs ?? []) as $b)
              <tr>
                <td>{{ $b->id_barang }}</td>
                <td>{{ $b->nama_barang }}</td>

                {{-- harga_satuan: format rupiah ID tanpa desimal --}}
                <td>
                  @php
                    $harga = is_null($b->harga_satuan) ? null : number_format((float)$b->harga_satuan, 0, ',', '.');
                  @endphp
                  {{ $harga !== null ? 'Rp '.$harga : '-' }}
                </td>

                {{-- gambar_url: thumbnail --}}
                <td>
                  @if(!empty($b->gambar_url))
                    <img src="{{ $b->gambar_url }}" alt="{{ $b->nama_barang }}" class="img-thumb">
                  @else
                    <span class="muted">-</span>
                  @endif
                </td>

                {{-- Tanggal Kadaluwarsa: format dd/mm/yy --}}
                <td>
                  @php
                    $raw  = $b->getRawOriginal('tanggal_kedaluwarsa'); // raw string dari DB
                  @endphp

                  @if($raw && $raw !== '0000-00-00')
                    @php
                      try {
                        $tgl  = \Carbon\Carbon::parse($raw);
                        $diff = now()->startOfDay()->diffInDays($tgl, false); // negatif = lewat
                        $label = $tgl->format('d/m/y');
                      } catch (\Throwable $e) {
                        $tgl = null; $diff = null; $label = null;
                      }
                    @endphp

                    @if($label)
                      {{ $label }}
                      @if($diff !== null && $diff < 0)
                        <span class="badge-exp">Kadaluarsa</span>
                      @elseif($diff !== null && $diff <= 7)
                        <span class="badge-soon">Hampir habis</span>
                      @endif
                    @else
                      <span class="muted">-</span>
                    @endif
                  @else
                    <span class="muted">-</span>
                  @endif
                </td>

                <td>{{ $b->stok_barang }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align:center;color:#6b7280;padding:16px">Tidak ada data.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- PAGINATION --}}
      @isset($barangs)
        @php
          $current = $barangs->currentPage();
          $last    = $barangs->lastPage();
          $start   = max(1, $current - 2);
          $end     = min($last, $current + 2);
          $pages   = range($start, $end);
        @endphp

        <div class="pagination">
          <a class="page-btn icon" href="{{ $barangs->previousPageUrl() ?? '#' }}">«</a>
          @foreach ($pages as $i)
            <a href="{{ $barangs->url($i) }}" class="page-btn @if($i == $current) is-active @endif">{{ $i }}</a>
          @endforeach
          <a class="page-btn icon" href="{{ $barangs->nextPageUrl() ?? '#' }}">»</a>
        </div>
      @endisset
    </div>
  </div>
</x-app-layout>

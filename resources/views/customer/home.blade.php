<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Beranda Pelanggan — SI Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Quicksand:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root{ --black:#000; --muted:#8a8a8a; --panel:#F0F0F0; --card:#F0EEED; --orange:#F25019; }
        html,body{height:100%;background:#fff}
        body{margin:0;font-family:Poppins,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}

        .wrap{display:grid;place-items:start center;min-height:100vh}
        .canvas{position:relative;width:1440px;min-height:1200px;background:#fff}
        .topbar{position:sticky;top:0;height:38px;background:#000;z-index:10}
        .brand{margin:25px 0 0 36px;font-weight:700;font-size:32px;line-height:48px}
        .title{margin:22px auto 10px; width:100%; text-align:center; font-weight:800; font-size:40px; line-height:64px}

        .search{
            width:562px;height:48px;margin:4px auto 40px;border-radius:62px;background:var(--panel);
            display:flex;align-items:center;gap:12px;padding:12px 16px;
        }
        .search img{width:24px;height:24px}
        .search input{all:unset;font-size:16px;flex:1;color:#000}

        .section{width:1310px;margin:0 auto}
        .section h3{font-family:Quicksand, Poppins, sans-serif;font-weight:600;font-size:20px;margin:0 0 14px 0}
        .grid{display:grid;grid-template-columns:repeat(6,159px);column-gap:65px;row-gap:28px}

        .card{
            width:159px;height:244px;background:var(--card);border-radius:20px;
            padding:10px 10px 12px; box-sizing:border-box; display:flex; flex-direction:column; align-items:center;
        }
        .imgwrap{ width:139px;height:96px;display:grid;place-items:center;margin-top:2px;margin-bottom:6px; }
        .imgwrap img{ max-width:100%;max-height:96px;object-fit:contain;display:block; }

        .name{
            width:100%;text-align:center;font-family:Quicksand, Poppins, sans-serif;
            font-weight:600;font-size:8px;line-height:10px;color:#000;margin:4px 0 10px;min-height:20px;
            display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
        }

        .qtyrow{display:flex;align-items:center;justify-content:space-between;width:121px;height:30px;margin:0 auto 8px}
        .btn-circle{
            width:30px;height:30px;border:1px solid #000;border-radius:10px;background:#fff;display:grid;place-items:center;cursor:pointer;
            padding:0;
        }
        .btn-circle img{width:30px;height:30px;display:block}
        .qty{font-family:Quicksand;font-weight:600;font-size:20px;line-height:25px;color:rgba(0,0,0,.5)}

        .add{
            width:136px;height:25px;border:0;border-radius:2px;background:#000;color:#FFF5F5;
            display:flex;align-items:center;justify-content:center;gap:5px;font-family:Quicksand;font-weight:500;font-size:14px;cursor:pointer
        }
        .add svg{width:13px;height:11px}
        .price{ margin-top:10px;font-family:Quicksand;font-weight:600;font-size:15px;line-height:19px;color:var(--orange) }

        .benefit{ width:1440px;background:#000000;margin-top:56px;padding:44px 0 60px;color:#fff }
        .benefit h4{margin:0 auto 18px;width:998px;text-align:center;font-weight:700;font-size:24px}
        .benefit .cols{width:900px;margin:12px auto 0;display:grid;grid-template-columns:repeat(3,1fr);gap:48px}
        .benefit .col{display:grid;justify-items:center;gap:10px;text-align:center}
        .benefit .ico{width:105px;height:105px;display:block;object-fit:contain}
        .benefit .title{font-weight:700;font-size:24px;line-height:32px}
        .benefit .desc{font-weight:400;font-size:16px;line-height:22px}

        /* Profil dan Keranjang Styling */
        .profile-cart {
            position: absolute;
            top: 25px;
            right: 36px;
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .profile-link img,
        .cart-link img {
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .cart-link {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12px;
        }

        .profile-link:hover,
        .cart-link:hover {
            opacity: 0.7;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="topbar"></div>
    <div class="wrap">
        <div class="canvas">
            <div class="brand">Muhammad Ilham</div>
            <div class="title">Mau Belanja apa hari ini ?</div>

            <!-- Form Pencarian -->
            <form class="search" method="get" action="{{ route('customer.home') }}">
                <img src="{{ asset('assets/icon-search.svg') }}" alt="Cari">
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari Barang...">
            </form>

            <!-- Profil dan Keranjang -->
            <div class="profile-cart">
                <a href="{{ route('profile.edit') }}" class="profile-link">
                    <img src="{{ asset('assets/profile-icon.svg') }}" alt="Profil">
                </a>
                <a href="{{ route('cart.index') }}" class="cart-link">
                    <img src="{{ asset('assets/cart-icon.svg') }}" alt="Keranjang">
                    <!-- Menampilkan jumlah item dalam keranjang jika ada -->
                    <span class="cart-count">{{ session('cart_count', 0) }}</span>
                </a>
            </div>

            {{-- ==================== REKOMENDASI ==================== --}}
            <section class="section">
                <h3>Produk Rekomendasi</h3>
                <div class="grid">
                    @forelse($recommended as $p)
                        <div class="card" x-data="{q:1}">
                            <div class="imgwrap">
                                <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_barang }}">
                            </div>
                            <div class="name">{{ $p->nama_barang }}</div>

                            <div class="qtyrow">
                                <button type="button" class="btn-circle" @click="q = Math.min(999, q+1)" aria-label="Tambah">
                                    <img src="{{ asset('assets/btn-plus.svg') }}" alt="+">
                                </button>
                                <div class="qty" x-text="q"></div>

                                <button type="button" class="btn-circle" @click="q = Math.max(0, q-1)" aria-label="Kurangi">
                                    <img x-bind:src="q > 0 ? '{{ asset('assets/btn-minus-active.svg') }}' : '{{ asset('assets/btn-minus-disabled.svg') }}'"
                                         x-bind:alt="q > 0 ? 'Minus aktif' : 'Minus nonaktif'">
                                </button>
                            </div>

                            <form method="post" action="{{ route('cart.add') }}" style="margin:0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id_barang }}">
                                <input type="hidden" name="qty" x-bind:value="q">
                                <button class="add" type="submit">
                                    <svg viewBox="0 0 13 11" xmlns="http://www.w3.org/2000/svg"><path d="M3.79 9.625a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0Zm5.958 0a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0ZM0 .458C0 .205.243 0 .542 0h2.166c.258 0 .48.154.531.368l.455 1.924h8.764c.161 0 .314.06.416.165.103.105.145.244.115.378l-.867 3.85a1.1 1.1 0 0 1-.575.803c-.296.201-.666.309-1.045.304H5.248a2.04 2.04 0 0 1-1.044-.304 1.1 1.1 0 0 1-.575-.804L2.724 2.86 2.264.917H.542C.243.917 0 .711 0 .458Z" fill="#FFF5F5"/></svg>
                                    Tambah
                                </button>
                            </form>

                            <div class="price">Rp {{ number_format($p->harga_satuan,0,',','.') }}</div>
                        </div>
                    @empty
                        @for($i=0;$i<6;$i++)
                            <div class="card" style="color:#999;display:grid;place-items:center;">Kosong</div>
                        @endfor
                    @endforelse
                </div>
            </section>

            {{-- ==================== TERLARIS ==================== --}}
            <section class="section" style="margin-top:32px">
                <h3>Terlaris</h3>
                <div class="grid">
                    @forelse($bestSellers as $p)
                        <div class="card" x-data="{q:1}">
                            <div class="imgwrap">
                                <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_barang }}">
                            </div>
                            <div class="name">{{ $p->nama_barang }}</div>

                            <div class="qtyrow">
                                <button type="button" class="btn-circle" @click="q=Math.min(999,q+1)" aria-label="Tambah">
                                    <img src="{{ asset('assets/btn-plus.svg') }}" alt="+">
                                </button>
                                <div class="qty" x-text="q"></div>
                                <button type="button" class="btn-circle" @click="q=Math.max(0,q-1)" aria-label="Kurangi">
                                    <img x-bind:src="q > 0 ? '{{ asset('assets/btn-minus-active.svg') }}' : '{{ asset('assets/btn-minus-disabled.svg') }}'"
                                         x-bind:alt="q > 0 ? 'Minus aktif' : 'Minus nonaktif'">
                                </button>
                            </div>

                            <form method="post" action="{{ route('cart.add') }}" style="margin:0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id_barang }}">
                                <input type="hidden" name="qty" x-bind:value="q">
                                <button class="add" type="submit">
                                    <svg viewBox="0 0 13 11" xmlns="http://www.w3.org/2000/svg"><path d="M3.79 9.625a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0Zm5.958 0a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0ZM0 .458C0 .205.243 0 .542 0h2.166c.258 0 .48.154.531.368l.455 1.924h8.764c.161 0 .314.06.416.165.103.105.145.244.115.378l-.867 3.85a1.1 1.1 0 0 1-.575.803c-.296.201-.666.309-1.045.304H5.248a2.04 2.04 0 0 1-1.044-.304 1.1 1.1 0 0 1-.575-.804L2.724 2.86 2.264.917H.542C.243.917 0 .711 0 .458Z" fill="#FFF5F5"/></svg>
                                    Tambah
                                </button>
                            </form>

                            <div class="price">Rp {{ number_format($p->harga_satuan,0,',','.') }}</div>
                        </div>
                    @empty
                        @for($i=0;$i<6;$i++)
                            <div class="card" style="color:#999;display:grid;place-items:center;">Kosong</div>
                        @endfor
                    @endforelse
                </div>
            </section>

            {{-- BENEFIT STRIP --}}
            <section class="benefit">
                <h4>Yuk, belanja di Toko Kelontong Bu Untung dan Dapatkan Keuntungannya!</h4>
                <div class="cols">
                    <div class="col">
                        <img class="ico" src="{{ asset('assets/logo-free-shipping.png') }}" alt="Gratis Ongkir">
                        <div class="title">Gratis Ongkir</div>
                        <div class="desc">Berapapun total belanjamu, bebas biaya kirim!</div>
                    </div>
                    <div class="col">
                        <img class="ico" src="{{ asset('assets/logo-sameday.png') }}" alt="Sameday Delivery">
                        <div class="title">Sameday Delivery</div>
                        <div class="desc">Pesananmu meluncur cepat dari toko Bu Untung</div>
                    </div>
                    <div class="col">
                        <img class="ico" src="{{ asset('assets/logo-easy.png') }}" alt="Belanja Jadi Mudah">
                        <div class="title">Belanja<br/>Jadi Mudah</div>
                        <div class="desc">Praktis dan mudah, belanja kapan saja!</div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</body>
</html> 


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Beranda Pelanggan — SI Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Quicksand:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root{ --black:#000; --muted:#8a8a8a; --panel:#F0F0F0; --card:#F0EEED; --orange:#F25019; }
        html,body{height:100%;background:#fff;scroll-behavior:smooth}
        body{margin:0;font-family:Poppins,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}

        /* Keyframe Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInBottom {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes ripple {
            to { transform: scale(2); opacity: 0; }
        }

        .wrap{display:grid;place-items:start center;min-height:100vh}
        .canvas{position:relative;width:1440px;min-height:1200px;background:#fff}
        
        .topbar{
            position:sticky;top:0;height:38px;background:#000;z-index:10;
            animation: fadeInDown 0.6s ease-out;
        }
        
        .brand{
            margin:25px 0 0 36px;font-weight:700;font-size:32px;line-height:48px;
            animation: slideInLeft 0.8s ease-out 0.2s both;
            transition: transform 0.3s ease, color 0.3s ease;
        }
        .brand:hover{
            transform: translateX(5px);
            color: var(--orange);
        }

        .title{
            margin:22px auto 10px; width:100%; text-align:center; font-weight:800; font-size:40px; line-height:64px;
            animation: fadeInUp 0.8s ease-out 0.3s both;
        }

        .search{
            width:562px;height:48px;margin:4px auto 40px;border-radius:62px;background:var(--panel);
            display:flex;align-items:center;gap:12px;padding:12px 16px;
            animation: bounce 0.6s ease-out 0.5s;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .search:hover{
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        .search:focus-within{
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(242,80,25,0.15);
            background: #fff;
        }
        .search img{width:24px;height:24px;transition:transform 0.3s ease}
        .search:focus-within img{transform:rotate(90deg) scale(1.1)}
        .search input{all:unset;font-size:16px;flex:1;color:#000}

        .section{width:1310px;margin:0 auto}
        .section h3{
            font-family:Quicksand, Poppins, sans-serif;font-weight:600;font-size:20px;margin:0 0 14px 0;
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .grid{display:grid;grid-template-columns:repeat(6,159px);column-gap:65px;row-gap:28px}

        .card{
            width:159px;height:244px;background:var(--card);border-radius:20px;
            padding:10px 10px 12px; box-sizing:border-box; display:flex; flex-direction:column; align-items:center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.15s; }
        .card:nth-child(3) { animation-delay: 0.2s; }
        .card:nth-child(4) { animation-delay: 0.25s; }
        .card:nth-child(5) { animation-delay: 0.3s; }
        .card:nth-child(6) { animation-delay: 0.35s; }
        .card:nth-child(n+7) { animation-delay: 0.4s; }

        .card:hover{
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
            background: #fff;
        }

        .imgwrap{ 
            width:139px;height:96px;display:grid;place-items:center;margin-top:2px;margin-bottom:6px;
            overflow: hidden;
            border-radius: 10px;
        }
        .imgwrap img{ 
            max-width:100%;max-height:96px;object-fit:contain;display:block;
            transition: transform 0.4s ease;
        }
        .card:hover .imgwrap img{
            transform: scale(1.15);
        }

        .name{
            width:100%;text-align:center;font-family:Quicksand, Poppins, sans-serif;
            font-weight:600;font-size:8px;line-height:10px;color:#000;margin:4px 0 10px;min-height:20px;
            display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
            transition: color 0.3s ease;
        }
        .card:hover .name{
            color: var(--orange);
        }

        .qtyrow{display:flex;align-items:center;justify-content:space-between;width:121px;height:30px;margin:0 auto 8px}
        .btn-circle{
            width:30px;height:30px;border:1px solid #000;border-radius:10px;background:#fff;display:grid;place-items:center;cursor:pointer;
            padding:0;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-circle::before{
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(242,80,25,0.2);
            border-radius: 50%;
            transform: scale(0);
            transition: transform 0.3s ease;
        }
        .btn-circle:hover{
            transform: scale(1.1);
            border-color: var(--orange);
            box-shadow: 0 2px 8px rgba(242,80,25,0.2);
        }
        .btn-circle:active{
            transform: scale(0.95);
        }
        .btn-circle:active::before{
            animation: ripple 0.6s ease-out;
        }
        .btn-circle img{width:30px;height:30px;display:block;transition:transform 0.2s ease}
        .btn-circle img{
  transition: transform .2s ease;     /* sudah ada—biarkan */
}

.btn-circle:hover img{
  transform: translateY(-2px) scale(1.06);  /* geser naik halus + sedikit membesar */
}

.btn-circle:active img{
  transform: translateY(-1px) scale(0.98);  /* feedback saat ditekan */
}

        .qty{
            font-family:Quicksand;font-weight:600;font-size:20px;line-height:25px;color:rgba(0,0,0,.5);
            transition: all 0.3s ease;
        }
        .card:hover .qty{
            color: var(--orange);
            transform: scale(1.1);
        }

        .add{
            width:136px;height:25px;border:0;border-radius:2px;background:#000;color:#FFF5F5;
            display:flex;align-items:center;justify-content:center;gap:5px;font-family:Quicksand;font-weight:500;font-size:14px;cursor:pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        .add::before{
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }
        .add:hover{
            background: var(--orange);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(242,80,25,0.4);
        }
        .add:hover::before{
            width: 200px;
            height: 200px;
        }
        .add:active{
            transform: translateY(0) scale(0.98);
        }
        .add svg{width:13px;height:11px;transition:transform 0.3s ease}
        .add:hover svg{
            transform: translateX(2px);
        }

        .price{ 
            margin-top:10px;font-family:Quicksand;font-weight:600;font-size:15px;line-height:19px;color:var(--orange);
            transition: all 0.3s ease;
        }
        .card:hover .price{
            transform: scale(1.1);
            font-size: 16px;
        }

        .benefit{ 
            width:1440px;background:#000000;margin-top:56px;padding:44px 0 60px;color:#fff;
            opacity: 0;
            animation: slideInBottom 0.8s ease-out forwards;
            animation-delay: 0.5s;
        }
        .benefit h4{
            margin:0 auto 18px;width:998px;text-align:center;font-weight:700;font-size:24px;
            animation: fadeInUp 0.8s ease-out 0.7s both;
        }
        .benefit .cols{width:900px;margin:12px auto 0;display:grid;grid-template-columns:repeat(3,1fr);gap:48px}
        .benefit .col{
            display:grid;justify-items:center;gap:10px;text-align:center;
            transition: transform 0.4s ease;
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .benefit .col:nth-child(1) { animation-delay: 0.8s; }
        .benefit .col:nth-child(2) { animation-delay: 0.95s; }
        .benefit .col:nth-child(3) { animation-delay: 1.1s; }
        
        .benefit .col:hover{
            transform: translateY(-10px);
        }
        .benefit .ico{
            width:105px;height:105px;display:block;object-fit:contain;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .benefit .col:hover .ico{
            transform: scale(1.15) rotate(5deg);
        }
        .benefit .title{
            font-weight:700;font-size:24px;line-height:32px;
            transition: color 0.3s ease;
        }
        .benefit .col:hover .title{
            color: var(--orange);
        }
        .benefit .desc{
            font-weight:400;font-size:16px;line-height:22px;
            transition: transform 0.3s ease;
        }
        .benefit .col:hover .desc{
            transform: scale(1.05);
        }

        /* Profil dan Keranjang Styling */
        .profile-cart {
            position: absolute;
            top: 25px;
            right: 36px;
            display: flex;
            gap: 30px;
            align-items: center;
            animation: slideInRight 0.8s ease-out 0.4s both;
        }

        .profile-link img,
        .cart-link img {
            width: 30px;
            height: 30px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .profile-link:hover img{
            transform: scale(1.2) rotate(360deg);
        }

        .cart-link {
            position: relative;
        }

        .cart-link:hover img{
            transform: scale(1.2) translateY(-3px);
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
            animation: pulse 2s ease-in-out infinite;
            box-shadow: 0 2px 8px rgba(255,0,0,0.4);
        }

        .profile-link:hover,
        .cart-link:hover {
            opacity: 0.85;
        }

        /* Scroll-triggered animations */
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }

        .scroll-reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Scroll reveal animation
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            // Observe sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('scroll-reveal');
                observer.observe(section);
            });
        });

        // Success feedback on add to cart
        document.addEventListener('alpine:init', () => {
            Alpine.magic('cartSuccess', () => {
                return (button) => {
                    button.style.transform = 'scale(1.1)';
                    button.style.background = '#10b981';
                    setTimeout(() => {
                        button.style.transform = '';
                        button.style.background = '';
                    }, 300);
                };
            });
        });
    </script>
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
                    <span class="cart-count">{{ session('cart_count', 0) }}</span>
                </a>
            </div>

            {{-- ==================== REKOMENDASI ==================== --}}
            <section class="section">
                <h3>Produk Rekomendasi</h3>
                <div class="grid">
                    @forelse($recommended as $p)
                        <div class="card" x-data="{jumlah_pesanan:1}">
                            <div class="imgwrap">
                                <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_barang }}">
                            </div>
                            <div class="name">{{ $p->nama_barang }}</div>

                            <div class="qtyrow">
                                <button type="button" class="btn-circle" @click="jumlah_pesanan = Math.min(999, jumlah_pesanan+1)" aria-label="Tambah">
                                    <img src="{{ asset('assets/btn-plus.svg') }}" alt="+">
                                </button>
                                <div class="qty" x-text="jumlah_pesanan"></div>

                                <button type="button" class="btn-circle" @click="jumlah_pesanan = Math.max(0, jumlah_pesanan-1)" aria-label="Kurangi">
                                    <img x-bind:src="jumlah_pesanan > 0 ? '{{ asset('assets/btn-minus-active.svg') }}' : '{{ asset('assets/btn-minus-disabled.svg') }}'"
                                         x-bind:alt="jumlah_pesanan > 0 ? 'Minus aktif' : 'Minus nonaktif'">
                                </button>
                            </div>

                            <form method="post" action="{{ route('cart.add') }}" style="margin:0" @submit="$cartSuccess($event.target.querySelector('.add'))">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $p->id_barang }}">
                                <input type="hidden" name="jumlah_pesanan" x-bind:value="jumlah_pesanan">
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
                        <div class="card" x-data="{jumlah_pesanan:1}">
                            <div class="imgwrap">
                                <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_barang }}">
                            </div>
                            <div class="name">{{ $p->nama_barang }}</div>

                            <div class="qtyrow">
                                <button type="button" class="btn-circle" @click="jumlah_pesanan=Math.min(999,jumlah_pesanan+1)" aria-label="Tambah">
                                    <img src="{{ asset('assets/btn-plus.svg') }}" alt="+">
                                </button>
                                <div class="qty" x-text="jumlah_pesanan"></div>
                                <button type="button" class="btn-circle" @click="jumlah_pesanan=Math.max(0,jumlah_pesanan-1)" aria-label="Kurangi">
                                    <img x-bind:src="jumlah_pesanan > 0 ? '{{ asset('assets/btn-minus-active.svg') }}' : '{{ asset('assets/btn-minus-disabled.svg') }}'"
                                         x-bind:alt="jumlah_pesanan > 0 ? 'Minus aktif' : 'Minus nonaktif'">
                                </button>
                            </div>

                            <form method="post" action="{{ route('cart.add') }}" style="margin:0" @submit="$cartSuccess($event.target.querySelector('.add'))">
                                @csrf
                                <input type="hidden" name="id_barang" value="{{ $p->id_barang }}">
                                <input type="hidden" name="jumlah_pesanan" x-bind:value="jumlah_pesanan">
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

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

@keyframes heroReveal {
  0% {
    opacity: 0;
    transform: translateY(0) scale(0.92);
  }
  60% {
    opacity: 0.8;
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}


.wrap{display:grid;place-items:start center;min-height:100vh}

.canvas{
    position:relative;
    width:100%;
    max-width:1200px;
    margin:0 auto;
    padding:0 24px;
    min-height:1200px;
    background:#fff;
}

.topbar{
    position:static;top:0;height:38px;background:#000;z-index:10;
    animation: fadeInDown 0.6s ease-out;
}

.brand{
    margin:25px 0 0 0;
    font-weight:700;
    font-size:32px;
    line-height:48px;
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

.section{
    width:100%;
    max-width:1200px;
    margin:0 auto;
}
.section h3{
    font-family:Quicksand, Poppins, sans-serif;font-weight:600;font-size:20px;margin:0 0 14px 0;
    opacity: 0;
    animation: fadeInUp 0.6s ease-out forwards;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
    gap:24px;
    justify-items:center;
}

.card{
    width:100%;
    max-width:200px;
    height:auto;
    background:var(--card);
    border-radius:20px;
    padding:14px 14px 16px;
    box-sizing:border-box;
    display:flex;
    flex-direction:column;
    align-items:center;
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
    width:100%;
    aspect-ratio:1 / 1;
    height:auto;
    background:#fff;
    border:1px solid #eee;
    border-radius:12px;
    display:grid;
    place-items:center;
    padding:8px;
    overflow:hidden;
    margin-top:2px;
    margin-bottom:10px;
}
.imgwrap img{
    width:88%;
    height:88%;
    object-fit:contain;
    display:block;
    transition: transform 0.25s ease;
}
.card:hover .imgwrap img{
    transform: scale(1.15);
}

.name{
    width:100%;
    text-align:center;
    font-family:Quicksand, Poppins, sans-serif;
    font-weight:600;
    font-size:13px;
    line-height:1.35;
    color:#111;
    margin:6px 0 12px;
    min-height:36px;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
    transition: color 0.3s ease;
}
.card:hover .name{
    color: var(--orange);
}

.qtyrow{display:flex;align-items:center;justify-content:space-between;width:121px;height:30px;margin:0 auto 8px}
.btn-circle{
  width:32px;
  height:32px;
  border:none;
  border-radius:10px;
  background:#fff;
  display:grid;
  place-items:center;
  cursor:pointer;
  padding:0;
  position: relative;
  overflow: hidden;

  box-shadow: 0 2px 6px rgba(0,0,0,.06);
  transition: transform .18s ease, box-shadow .18s ease;
}

.btn-circle::before{
  content:'';
  position:absolute;
  inset:0;
  background: rgba(0,0,0,0.06);
  border-radius:50%;
  transform: scale(0);
  transition: transform .3s ease;
}

.btn-circle:hover{
  transform: translateY(-1px) scale(1.06);
  box-shadow: 0 8px 18px rgba(0,0,0,.14);
}

.btn-circle:active{
  transform: translateY(0) scale(0.98);
}
.btn-circle:active::before{
  transform: scale(1.8);
}

.btn-circle img{
  width:30px; height:30px; display:block; transition: transform .18s ease;
}

.btn-circle:hover img{ transform: translateY(-1px) scale(1.04); }
.btn-circle:active img{ transform: translateY(0) scale(.99); }

.btn-circle:disabled{
  opacity: 0.5;
  cursor: not-allowed;
}

.qty{
    font-family:Quicksand;
    font-weight:600;
    font-size:16px;
    line-height:1;
    color:rgba(0,0,0,.7);
    transition: all 0.3s ease;
}
.card:hover .qty{
    color: var(--orange);
    transform: scale(1.1);
}

.add{
    width:136px;
    height:34px;
    border:0;
    border-radius:8px;
    background:#000;
    color:#FFF5F5;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:5px;
    font-family:Quicksand;
    font-weight:500;
    font-size:14px;
    cursor:pointer;
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
.add:disabled{
    opacity: 0.6;
    cursor: not-allowed;
}
.add svg{width:13px;height:11px;transition:transform 0.3s ease}
.add:hover svg{
    transform: translateX(2px);
}

.price{
    margin-top:12px;
    font-family:Quicksand;
    font-weight:700;
    font-size:18px;
    line-height:1.2;
    color:var(--orange);
    transition: all 0.3s ease;
}
.card:hover .price{
    transform: scale(1.1);
    font-size: 18px;
}

body{ overflow-x: hidden; }

.benefit{
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;

  width: 100vw;
  background:#000;
  color:#fff;

  margin-top:56px;
  padding:44px 24px 60px;

  opacity: 0;
  animation: slideInBottom 0.8s ease-out forwards;
  animation-delay: 0.5s;
}

.benefit h4{
  max-width:998px;
  margin:0 auto 18px;
  text-align:center;
  font-weight:700;
  font-size:24px;
  animation: fadeInUp 0.8s ease-out 0.7s both;
}
.benefit .cols{
  width:100%;
  max-width:900px;
  margin:12px auto 0;
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:48px;
}
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

.profile-cart {
    position: absolute;
    top: 25px;
    right: 24px;
    display: flex;
    gap: 30px;
    align-items: center;
    animation: slideInRight 0.8s ease-out 0.4s both;
}

.profile-link img,
.cart-link img {
    width: 45px;
    height: 45px;
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

.scroll-reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease-out;
}

.scroll-reveal.active {
    opacity: 1;
    transform: translateY(0);
}

.loading-spinner {
    border: 2px solid #f3f3f3;
    border-top: 2px solid var(--orange);
    border-radius: 50%;
    width: 16px;
    height: 16px;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

.header-stack {
  display: grid;
  gap: 10px;
}

.brand  { order: 1; }
.title  { order: 2; }
.search { order: 3; }
.hero-in{ order: 4; }

@media (max-width: 640px) {
  .brand  { order: 1; }
  .title  { order: 2; }
  .search { order: 3; }
  .hero-in{ order: 4; }
}

.hero-in{
  display:block;
  width:100%;
  height:auto;
  margin:12px 0 36px;
  border-radius:20px;
  box-shadow:0 6px 20px rgba(0,0,0,.08);
  max-height:340px;

  opacity: 0;
  animation: heroReveal 1s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
  will-change: transform, opacity, filter;
  backface-visibility: hidden;
}
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
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

            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('scroll-reveal');
                observer.observe(section);
            });

            const cartCount = {{ session('cart_unique_count', 0) }};
            updateCartBadge(cartCount);
        });

        function updateCartBadge(count) {
            const badge = document.querySelector('.cart-count');
            if (badge) {
                badge.textContent = count > 99 ? '99+' : count;
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 80px;
                right: 24px;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                animation: slideInRight 0.3s ease-out;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideInRight 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</head>
<body>
    <div class="topbar"></div>
    <div class="wrap">
        <div class="canvas">
@php
    $u = Auth::guard('pelanggan')->user();
    $nama = optional($u)->username ?? optional($u)->name ?? 'Sahabat';
    $jam  = now('Asia/Jakarta')->format('H');
    $waktu = $jam < 11 ? 'pagi' : ($jam < 15 ? 'siang' : ($jam < 18 ? 'sore' : 'malam'));
@endphp

<div class="header-stack">
<div class="brand">
  Selamat {{ $waktu }}, {{ $nama }}!
</div>

<div class="title">
  Mau belanja apa hari ini?
</div>

<form class="search" method="get" action="{{ route('customer.search') }}">
    <img src="{{ asset('assets/icon-search.svg') }}" alt="Cari" id="search-icon" style="cursor: pointer;">
    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari Barang..." id="search-input">
</form>

<script>
    document.getElementById('search-icon').addEventListener('click', function() {
        document.querySelector('.search').submit();
    });

    document.getElementById('search-input').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            document.querySelector('.search').submit();
        }
    });
</script>

<img class="hero-in"
     src="{{ asset('assets/hero-bu-untung.svg') }}"
     alt="Toko Kelontong Bu Untung — Dekat, Hemat, Bersahabat"
     loading="eager" decoding="async">

</div>
    
<div class="profile-cart">
    <a href="{{ route('profile.edit') }}" class="profile-link">
        <img src="{{ asset('assets/profile-icon.svg') }}" alt="Profil">
    </a>

    @php
        $unique = (int) session('cart_unique_count', 0);
        $badge  = $unique > 99 ? '99+' : $unique;
    @endphp

    <a href="{{ route('cart.index') }}" class="cart-link" aria-label="Buka keranjang">
        <img src="{{ asset('assets/cart-icon.svg') }}" alt="Keranjang">
        <span class="cart-count" aria-live="polite" aria-atomic="true">{{ $badge }}</span>
    </a>
</div>

{{-- ==================== REKOMENDASI ==================== --}}
<section class="section">
    <h3>Produk Rekomendasi</h3>
    <div class="grid">
        @forelse($recommended as $p)
            @if($loop->iteration > 5) @break @endif
            <div class="card" 
                 x-data="{
                     idBarang: '{{ $p->id_barang }}',
                     inCart: {{ session('cart') && array_key_exists($p->id_barang, session('cart')) ? 'true' : 'false' }},
                     jumlah_pesanan: {{ session('cart')[$p->id_barang] ?? 0 }},
                     loading: false,
                     
                     async addToCart() {
                         this.loading = true;
                         try {
                             const formData = new FormData();
                             formData.append('_token', '{{ csrf_token() }}');
                             formData.append('id_barang', this.idBarang);
                             formData.append('jumlah_pesanan', 1);

                             const response = await fetch('{{ route('cart.add') }}', {
                                 method: 'POST',
                                 body: formData,
                                 headers: { 'X-Requested-With': 'XMLHttpRequest' }
                             });

                             const data = await response.json();

                             if (data.success) {
                                 this.inCart = true;
                                 this.jumlah_pesanan = 1;
                                 updateCartBadge(data.cart_count);
                                 showNotification('Produk ditambahkan ke keranjang!');
                             } else {
                                 showNotification(data.message || 'Gagal menambahkan produk', 'error');
                             }
                         } catch (error) {
                             console.error('Error:', error);
                             showNotification('Terjadi kesalahan, silakan coba lagi', 'error');
                         } finally {
                             this.loading = false;
                         }
                     },

                     async updateQuantity(change) {
                         const newQty = this.jumlah_pesanan + change;
                         if (newQty < 0) return;
                         
                         if (newQty === 0) {
                             this.inCart = false;
                             this.jumlah_pesanan = 0;
                             await this.removeFromCart();
                             return;
                         }
                         
                         this.loading = true;
                         try {
                             const formData = new FormData();
                             formData.append('_token', '{{ csrf_token() }}');
                             formData.append('id_barang', this.idBarang);
                             formData.append('jumlah_pesanan', newQty);
                             
                             const response = await fetch('{{ route('cart.update') }}', {
                                 method: 'POST',
                                 body: formData,
                                 headers: { 'X-Requested-With': 'XMLHttpRequest' }
                             });
                             
                             const data = await response.json();
                             
                             if (data.success) {
                                 this.jumlah_pesanan = newQty;
                                 updateCartBadge(data.cart_count || {{ $unique }});
                             } else {
                                 showNotification(data.message || 'Gagal update quantity', 'error');
                             }
                         } catch (error) {
                             console.error('Error:', error);
                             showNotification('Terjadi kesalahan, silakan coba lagi', 'error');
                         } finally {
                             this.loading = false;
                         }
                     },

                     async removeFromCart() {
                         try {
                             const response = await fetch('{{ route('cart.remove', $p->id_barang) }}', {
                                 method: 'POST',
                                 headers: {
                                     'X-Requested-With': 'XMLHttpRequest',
                                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                 }
                             });

                             const data = await response.json();
                             
                             if (data.success) {
                                 updateCartBadge(data.cart_count || 0);
                                 showNotification('Produk dihapus dari keranjang');
                             }
                         } catch (error) {
                             console.error('Error:', error);
                         }
                     }
                 }">

                <div class="imgwrap">
                    <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_barang }}">
                </div>
                <div class="name">{{ $p->nama_barang }}</div>

                <div class="qtyrow" x-show="inCart" x-transition>
                    <button type="button" 
                            class="btn-circle" 
                            @click="updateQuantity(-1)" 
                            :disabled="loading"
                            aria-label="Kurangi">
                        <img :src="jumlah_pesanan > 0 ? '{{ asset('assets/btn-minus-active.svg') }}' : '{{ asset('assets/btn-minus-disabled.svg') }}'"
                             :alt="jumlah_pesanan > 0 ? 'Minus aktif' : 'Minus nonaktif'">
                    </button>
                    <div class="qty" x-text="jumlah_pesanan"></div>
                    <button type="button" 
                            class="btn-circle" 
                            @click="updateQuantity(1)" 
                            :disabled="loading"
                            aria-label="Tambah">
                        <img src="{{ asset('assets/btn-plus.svg') }}" alt="+">
                    </button>
                </div>

                <button class="add" 
                        type="button"
                        x-show="!inCart"
                        @click="addToCart()"
                        :disabled="loading"
                        x-transition>
                    <template x-if="!loading">
                        <svg viewBox="0 0 13 11" xmlns="http://www.w3.org/2000/svg"><path d="M3.79 9.625a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0Zm5.958 0a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0ZM0 .458C0 .205.243 0 .542 0h2.166c.258 0 .48.154.531.368l.455 1.924h8.764c.161 0 .314.06.416.165.103.105.145.244.115.378l-.867 3.85a1.1 1.1 0 0 1-.575.803c-.296.201-.666.309-1.045.304H5.248a2.04 2.04 0 0 1-1.044-.304 1.1 1.1 0 0 1-.575-.804L2.724 2.86 2.264.917H.542C.243.917 0 .711 0 .458Z" fill="#FFF5F5"/></svg>
                    </template>
                    <template x-if="loading">
                        <div class="loading-spinner"></div>
                    </template>
                    <span x-show="!loading">Tambah</span>
                </button>

                <div class="price">Rp {{ number_format($p->harga_satuan,0,',','.') }}</div>
            </div>
        @empty
            @for($i=0;$i<5;$i++)
                <div class="card" style="color:#999;display:grid;place-items:center;">Kosong</div>
            @endfor
        @endforelse
    </div>
</section>

{{-- ==================== TERLARIS ==================== --}}
<section class="section" style="margin-top:32px">
    <h3>Produk Terlaris</h3>
    <div class="grid">
        @forelse($bestSellers as $p)
            @if($loop->iteration > 10) @break @endif
            <div class="card" 
                 x-data="{
                     idBarang: '{{ $p->id_barang }}',
                     inCart: {{ session('cart') && array_key_exists($p->id_barang, session('cart')) ? 'true' : 'false' }},
                     jumlah_pesanan: {{ session('cart')[$p->id_barang] ?? 0 }},
                     loading: false,
                     
                     async addToCart() {
                         this.loading = true;
                         try {
                             const formData = new FormData();
                             formData.append('_token', '{{ csrf_token() }}');
                             formData.append('id_barang', this.idBarang);
                             formData.append('jumlah_pesanan', 1);

                             const response = await fetch('{{ route('cart.add') }}', {
                                 method: 'POST',
                                 body: formData,
                                 headers: { 'X-Requested-With': 'XMLHttpRequest' }
                             });

                             const data = await response.json();

                             if (data.success) {
                                 this.inCart = true;
                                 this.jumlah_pesanan = 1;
                                 updateCartBadge(data.cart_count);
                                 showNotification('Produk ditambahkan ke keranjang!');
                             } else {
                                 showNotification(data.message || 'Gagal menambahkan produk', 'error');
                             }
                         } catch (error) {
                             console.error('Error:', error);
                             showNotification('Terjadi kesalahan, silakan coba lagi', 'error');
                         } finally {
                             this.loading = false;
                         }
                     },

                     async updateQuantity(change) {
                         const newQty = this.jumlah_pesanan + change;
                         if (newQty < 0) return;
                         
                         if (newQty === 0) {
                             this.inCart = false;
                             this.jumlah_pesanan = 0;
                             await this.removeFromCart();
                             return;
                         }
                         
                         this.loading = true;
                         try {
                             const formData = new FormData();
                             formData.append('_token', '{{ csrf_token() }}');
                             formData.append('id_barang', this.idBarang);
                             formData.append('jumlah_pesanan', newQty);
                             
                             const response = await fetch('{{ route('cart.update') }}', {
                                 method: 'POST',
                                 body: formData,
                                 headers: { 'X-Requested-With': 'XMLHttpRequest' }
                             });
                             
                             const data = await response.json();
                             
                             if (data.success) {
                                 this.jumlah_pesanan = newQty;
                                 updateCartBadge(data.cart_count || {{ $unique }});
                             } else {
                                 showNotification(data.message || 'Gagal update quantity', 'error');
                             }
                         } catch (error) {
                             console.error('Error:', error);
                             showNotification('Terjadi kesalahan, silakan coba lagi', 'error');
                         } finally {
                             this.loading = false;
                         }
                     },

                     async removeFromCart() {
                         try {
                             const response = await fetch('{{ route('cart.remove', $p->id_barang) }}', {
                                 method: 'POST',
                                 headers: {
                                     'X-Requested-With': 'XMLHttpRequest',
                                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                 }
                             });

                             const data = await response.json();
                             
                             if (data.success) {
                                 updateCartBadge(data.cart_count || 0);
                                 showNotification('Produk dihapus dari keranjang');
                             }
                         } catch (error) {
                             console.error('Error:', error);
                         }
                     }
                 }">

                <div class="imgwrap">
                    <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_barang }}">
                </div>
                <div class="name">{{ $p->nama_barang }}</div>

                <div class="qtyrow" x-show="inCart" x-transition>
                    <button type="button" 
                            class="btn-circle" 
                            @click="updateQuantity(-1)" 
                            :disabled="loading"
                            aria-label="Kurangi">
                        <img :src="jumlah_pesanan > 0 ? '{{ asset('assets/btn-minus-active.svg') }}' : '{{ asset('assets/btn-minus-disabled.svg') }}'"
                             :alt="jumlah_pesanan > 0 ? 'Minus aktif' : 'Minus nonaktif'">
                    </button>
                    <div class="qty" x-text="jumlah_pesanan"></div>
                    <button type="button" 
                            class="btn-circle" 
                            @click="updateQuantity(1)" 
                            :disabled="loading"
                            aria-label="Tambah">
                        <img src="{{ asset('assets/btn-plus.svg') }}" alt="+">
                    </button>
                </div>

                <button class="add" 
                        type="button"
                        x-show="!inCart"
                        @click="addToCart()"
                        :disabled="loading"
                        x-transition>
                    <template x-if="!loading">
                        <svg viewBox="0 0 13 11" xmlns="http://www.w3.org/2000/svg"><path d="M3.79 9.625a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0Zm5.958 0a1.08 1.08 0 1 1 2.167 0 1.08 1.08 0 0 1-2.167 0ZM0 .458C0 .205.243 0 .542 0h2.166c.258 0 .48.154.531.368l.455 1.924h8.764c.161 0 .314.06.416.165.103.105.145.244.115.378l-.867 3.85a1.1 1.1 0 0 1-.575.803c-.296.201-.666.309-1.045.304H5.248a2.04 2.04 0 0 1-1.044-.304 1.1 1.1 0 0 1-.575-.804L2.724 2.86 2.264.917H.542C.243.917 0 .711 0 .458Z" fill="#FFF5F5"/></svg>
                    </template>
                    <template x-if="loading">
                        <div class="loading-spinner"></div>
                    </template>
                    <span x-show="!loading">Tambah</span>
                </button>

                <div class="price">Rp {{ number_format($p->harga_satuan,0,',','.') }}</div>
            </div>
        @empty
            @for($i=0;$i<10;$i++)
                <div class="card" style="color:#999;display:grid;place-items:center;">Kosong</div>
            @endfor
        @endforelse
    </div>
</section>

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
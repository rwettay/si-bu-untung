<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja — Toko Kelontong Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Quicksand:wght@500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Poppins, sans-serif;
            background-color: #ffffff;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: #ffffff;
            border-bottom: 1px solid #e5e5e5;
        }

        .user-name {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon {
            width: 32px;
            height: 32px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .icon:hover {
            transform: scale(1.1);
        }

        /* Back Button */
        .back-button {
            padding: 20px 40px;
            background-color: #ffffff;
        }

        .back-arrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
            cursor: pointer;
            color: #000000;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .back-arrow:hover {
            transform: translateX(-4px);
        }

        .back-arrow img {
            width: 24px;
            height: 24px;
        }

        /* Main Container */
        .container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            padding: 30px 40px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Left Column - Cart Items */
        .cart-section {
            width: 100%;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .cart-title {
            font-size: 24px;
            font-weight: 700;
            color: #000000;
        }

        .delete-all {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: #000000;
            font-size: 14px;
            cursor: pointer;
            font-family: Poppins, sans-serif;
            transition: color 0.2s;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .delete-all:hover {
            color: #ff5722;
            background-color: #fff5f5;
        }

        .delete-all img {
            width: 20px;
            height: 20px;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 180px 1fr auto;
            gap: 20px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 12px;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s, opacity 0.3s;
        }

        .cart-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .item-image-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .cart-item .product-image {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-name {
            font-size: 14px;
            font-weight: 500;
            color: #000000;
            text-align: center;
        }

        .item-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .quantity-btn {
            width: 36px;
            height: 36px;
            background-color: #ffffff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            padding: 0;
        }

        .quantity-btn img {
            width: 18px;
            height: 18px;
        }

        .quantity-btn:hover:not(:disabled) {
            background-color: #f0f0f0;
            transform: scale(1.05);
        }

        .quantity-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .quantity-value {
            font-size: 18px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: #ff5722;
            margin-left: 20px;
        }

        .item-actions {
            display: flex;
            align-items: center;
        }

        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 6px;
            transition: background-color 0.2s, transform 0.2s;
        }

        .remove-btn:hover {
            background-color: #fff5f5;
            transform: scale(1.1);
        }

        .remove-btn img {
            width: 24px;
            height: 24px;
        }

        /* Empty Cart State */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #666666;
        }

        .empty-cart img {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-cart h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #000000;
        }

        .empty-cart p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .empty-cart a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #ff5722;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .empty-cart a:hover {
            background-color: #e64a19;
        }

        /* Right Column - Order Summary */
        .summary-section {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .order-summary {
            background-color: #f5f5f5;
            border-radius: 12px;
            padding: 30px;
        }

        .summary-title {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 25px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .summary-label {
            color: #666666;
        }

        .summary-value {
            font-weight: 600;
            color: #000000;
        }

        .summary-divider {
            height: 1px;
            background-color: #dddddd;
            margin: 20px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .total-label {
            font-size: 18px;
            font-weight: 700;
            color: #000000;
        }

        .total-value {
            font-size: 18px;
            font-weight: 700;
            color: #000000;
        }

        .checkout-btn {
            width: 100%;
            padding: 15px;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: Poppins, sans-serif;
            transition: background-color 0.2s, transform 0.2s;
        }

        .checkout-btn:hover:not(:disabled) {
            background-color: #e64a19;
            transform: translateY(-2px);
        }

        .checkout-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading Spinner */
        .loading {
            pointer-events: none;
            opacity: 0.6;
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 24px;
            height: 24px;
            margin: -12px 0 0 -12px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #ff5722;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #000000;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .toast.success {
            background-color: #4caf50;
        }

        .toast.error {
            background-color: #ff5722;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateY(0);
                opacity: 1;
            }
            to {
                transform: translateY(-20px);
                opacity: 0;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }
            to {
                opacity: 0;
                transform: scale(0.8);
            }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease-out;
        }

        .modal.active {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: scaleIn 0.2s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-content h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #000000;
        }

        .modal-content p {
            font-size: 14px;
            color: #666666;
            margin-bottom: 25px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: Poppins, sans-serif;
            transition: all 0.2s;
        }

        .modal-btn.confirm {
            background-color: #ff5722;
            color: white;
        }

        .modal-btn.confirm:hover {
            background-color: #e64a19;
            transform: translateY(-2px);
        }

        .modal-btn.cancel {
            background-color: #f5f5f5;
            color: #000000;
        }

        .modal-btn.cancel:hover {
            background-color: #e5e5e5;
        }

        /* Footer */
        .footer {
            background-color: #000000;
            color: #ffffff;
            padding: 60px 40px;
            margin-top: 60px;
        }

        .footer-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-subtitle {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            margin-top: 40px;
        }

        .footer-text {
            font-size: 14px;
            line-height: 1.8;
            color: #cccccc;
            max-width: 900px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
            }

            .summary-section {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .user-name {
                font-size: 20px;
            }

            .back-button {
                padding: 15px 20px;
            }

            .container {
                padding: 20px;
            }

            .cart-item {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .item-image-wrapper {
                justify-self: center;
            }

            .item-controls {
                justify-content: space-between;
                flex-wrap: wrap;
            }

            .item-price {
                margin-left: 0;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="user-name">Muhammad Ilham</div>
        <div class="header-icons">
            <img src="{{ asset('assets/cart-icon.svg') }}" alt="Shopping cart icon showing items in basket" class="icon" />
            <img src="{{ asset('assets/profile-icon.svg') }}" alt="User profile avatar icon in circular frame" class="icon" />
        </div>
    </div>

    <!-- Back Button -->
    <div class="back-button">
        <a href="/home" class="back-arrow">
            <img src="{{ asset('assets/back-arrow.png') }}" alt="Back arrow icon pointing left for navigation" />
            Kembali
        </a>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Column - Cart Items -->
        <div class="cart-section">
            <div class="cart-header">
                <h2 class="cart-title">Keranjang</h2>
                <button class="delete-all" onclick="confirmClearCart()" {{ empty($cart) || count($cart) === 0 ? 'style="display:none;"' : '' }}>
                    <img src="{{ asset('assets/trash-icon.png') }}" alt="Trash bin icon for deleting all items" />
                    Hapus Semua
                </button>
            </div>

            <div class="cart-items" id="cartItems">
                @if(empty($cart) || count($cart) === 0)
                    <div class="empty-cart">
                        <img src="{{ asset('assets/empty-cart.png') }}" alt="Empty shopping cart icon with simple outline design showing no items inside" />
                        <h3>Keranjang Kosong</h3>
                        <p>Belum ada produk di keranjang belanja Anda</p>
                        <a href="/home">Mulai Belanja</a>
                    </div>
                @else
                    @foreach ($cart as $id_barang => $qty)
                        @php
                            $product = $products->firstWhere('id_barang', $id_barang);
                        @endphp
                        @if($product)
                        <div class="cart-item"
     data-product-id="{{ $id_barang }}"
     data-remove-url="{{ route('cart.remove', $id_barang) }}">
                            <div class="item-image-wrapper">
                                <img src="{{ $product->gambar_url }}" alt="Product image of {{ $product->nama_barang }} displayed in shopping cart" class="product-image" />
                                <div class="item-name">{{ $product->nama_barang }}</div>
                            </div>

                            <div class="item-controls">
                                <div class="item-quantity">
                                    <button class="quantity-btn" onclick="updateQuantity('{{ $id_barang }}', -1)" aria-label="Decrease quantity of {{ $product->nama_barang }}">
                                        <img src="{{ asset('assets/btn-minus-active.svg') }}" alt="Minus button icon for decreasing item quantity" />
                                    </button>
                                    <span class="quantity-value" data-quantity="{{ $qty }}">{{ $qty }}</span>
                                    <button class="quantity-btn" onclick="updateQuantity('{{ $id_barang }}', 1)" aria-label="Increase quantity of {{ $product->nama_barang }}">
                                        <img src="{{ asset('assets/btn-plus.svg') }}" alt="Plus button icon for increasing item quantity" />
                                    </button>
                                </div>
                                <div class="item-price" data-price="{{ $product->harga_satuan }}">Rp {{ number_format($product->harga_satuan * $qty, 0, ',', '.') }}</div>
                            </div>

                            <div class="item-actions">
                                <button class="remove-btn" onclick="confirmRemoveItem('{{ $id_barang }}')" aria-label="Remove {{ $product->nama_barang }} from cart">
                                    <img src="{{ asset('assets/trash-icon.png') }}" alt="Red trash bin icon for removing single item from shopping cart" />
                                </button>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Column - Order Summary -->
        <div class="summary-section">
            <div class="order-summary">
                <h3 class="summary-title">Ringkasan Pesanan</h3>

                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="subtotalValue">Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Diskon</span>
                    <span class="summary-value">Rp 0</span>
                </div>

                <div class="summary-divider"></div>

                <div class="total-row">
                    <span class="total-label">Total Belanja</span>
                    <span class="total-value" id="totalValue">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                </div>

                <button class="checkout-btn" {{ empty($cart) || count($cart) === 0 ? 'disabled' : '' }}>Beli Sekarang</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <h3 id="modalTitle">Konfirmasi</h3>
            <p id="modalMessage">Apakah Anda yakin?</p>
            <div class="modal-actions">
                <button class="modal-btn cancel" onclick="closeModal()">Batal</button>
                <button class="modal-btn confirm" id="modalConfirmBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <h2 class="footer-title">Toko Kelontong Bu Untung, Belanja menjadi mudah</h2>
        <p class="footer-text">
            Toko Kelontong Bu Untung adalah usaha ritel di Kebumen yang menyediakan berbagai kebutuhan pokok seperti beras, gula, minyak goreng, makanan ringan, serta barang rumah tangga lainnya. Toko ini melayani warga sekitar dan menjadi tempat belanja yang praktis serta terjangkau.
        </p>

        <h3 class="footer-subtitle">Cara Belanja di Toko Kelontong Bu Untung</h3>
        <p class="footer-text">
            Belanja kebutuhan harian kini jadi lebih praktis dan hemat waktu. Tanpa perlu keluar rumah atau menghadapi kemacetan, kamu bisa memenuhi semua kebutuhan hanya melalui website Toko Kelontong Bu Untung.
        </p>
        <p class="footer-text">
            Cukup buka website ini, pilih produk yang kamu butuhkan, lalu selesaikan pembayaran dengan mudah menggunakan QRIS. Tidak perlu install aplikasi, tidak ada biaya tambahan—semua pesanan dikirim langsung tanpa ribet! Belanja jadi lebih santai, cepat, dan menyenangkan, langsung dari HP atau laptop, kapan pun kamu butuh. Toko Kelontong Bu Untung siap melayani kamu dari berbagai penjuru Indonesia, dengan produk lengkap dan harga terjangkau.
        </p>
    </div>

    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show toast notification
        function showToast(message, type = 'success') {
            // Remove existing toasts
            const existingToasts = document.querySelectorAll('.toast');
            existingToasts.forEach(toast => toast.remove());

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 2700);
        }

        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Update cart totals
        function updateCartTotals() {
            let subtotal = 0;
            const items = document.querySelectorAll('.cart-item');
            
            items.forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-value').getAttribute('data-quantity'));
                const price = parseInt(item.querySelector('.item-price').getAttribute('data-price'));
                subtotal += quantity * price;
            });

            document.getElementById('subtotalValue').textContent = formatCurrency(subtotal);
            document.getElementById('totalValue').textContent = formatCurrency(subtotal);

            // Enable/disable checkout button
            const checkoutBtn = document.querySelector('.checkout-btn');
            checkoutBtn.disabled = items.length === 0;

            // Show/hide delete all button
            const deleteAllBtn = document.querySelector('.delete-all');
            if (items.length === 0) {
                deleteAllBtn.style.display = 'none';
            } else {
                deleteAllBtn.style.display = 'flex';
            }
        }

        // Update quantity
        async function updateQuantity(id_barang, change) {
    const item = document.querySelector(`[data-product-id="${id_barang}"]`);
    if (!item) return;

    const jumlahEl = item.querySelector('.quantity-value');
    const currentJumlah = parseInt(jumlahEl.getAttribute('data-quantity'));
    const newJumlah = currentJumlah + change;

    if (newJumlah < 1) {
        confirmRemoveItem(id_barang);
        return;
    }

    item.classList.add('loading');

    try {
const response = await fetch('/cart/update', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        id_barang: id_barang,
        jumlah_pesanan: newJumlah
    })
});


        const data = await response.json();
        if (data.success) {
            jumlahEl.textContent = newJumlah;
            jumlahEl.setAttribute('data-quantity', newJumlah);

            const price = parseInt(item.querySelector('.item-price').getAttribute('data-price'));
            item.querySelector('.item-price').textContent = formatCurrency(price * newJumlah);
            updateCartTotals();
            showToast('Jumlah produk diperbarui', 'success');
        } else {
            showToast(data.message || 'Gagal memperbarui jumlah produk', 'error');
        }
    } catch (error) {
        console.error('Error updating jumlah_pesanan:', error);
        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
    } finally {
        item.classList.remove('loading');
    }
}


        // Remove single item
        async function removeItem(id_barang) {
  const item = document.querySelector(`[data-product-id="${id_barang}"]`);
  if (!item) return;

  item.classList.add('loading'); // Menambahkan animasi loading

  try {
const url = item.getAttribute('data-remove-url'); // URL dari Blade

const res = await fetch(url, {
  method: 'POST', // kirim POST...
  headers: {
    'X-CSRF-TOKEN': csrfToken,
    'Accept': 'application/json',
    'Content-Type': 'application/x-www-form-urlencoded'
  },
  body: new URLSearchParams({ _method: 'DELETE' }) // ... spoof DELETE untuk Laravel
});


    const data = await res.json();  // Menangani respons JSON

    if (data.success) {
      item.style.animation = 'fadeOut .3s ease-out';  // Animasi
      setTimeout(() => {
        item.remove();  // Hapus item dari UI
        updateCartTotals();  // Perbarui total keranjang
      }, 300);
      showToast('Produk berhasil dihapus', 'success');  // Pesan sukses
    } else {
      showToast(data.message || 'Gagal menghapus produk', 'error');  // Pesan error
      item.classList.remove('loading');
    }
  } catch (e) {
    console.error(e);
    showToast('Terjadi kesalahan. Coba lagi.', 'error');  // Pesan kesalahan umum
    item.classList.remove('loading');
  }
}


        // Clear entire cart
        async function clearCart() {
            const cartSection = document.querySelector('.cart-items');
            cartSection.classList.add('loading');

            try {
                const response = await fetch('/cart/clear', {
    method: 'DELETE',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    }
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Keranjang berhasil dikosongkan', 'success');
                    
                    // Reload page after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    cartSection.classList.remove('loading');
                    showToast(data.message || 'Gagal mengosongkan keranjang', 'error');
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
                cartSection.classList.remove('loading');
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        }

        // Modal functions
        let modalConfirmCallback = null;

        function showModal(title, message, confirmCallback) {
            const modal = document.getElementById('confirmModal');
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            
            modalConfirmCallback = confirmCallback;
            
            modal.classList.add('active');
        }

        function closeModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('active');
            modalConfirmCallback = null;
        }

        function confirmRemoveItem(id_barang) {
            showModal(
                'Hapus Produk',
                'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
                () => removeItem(id_barang)
            );
        }

        function confirmClearCart() {
            showModal(
                'Hapus Semua Produk',
                'Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?',
                () => clearCart()
            );
        }

        // Modal confirm button handler
        document.getElementById('modalConfirmBtn').addEventListener('click', function() {
            if (modalConfirmCallback) {
                modalConfirmCallback();
            }
            closeModal();
        });

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('confirmModal');
                if (modal.classList.contains('active')) {
                    closeModal();
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartTotals();
        });
    </script>
</body>
</html>
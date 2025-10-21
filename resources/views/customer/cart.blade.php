<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Keranjang Belanja — SI Bu Untung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Quicksand:wght@500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Poppins, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .wrap {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .brand {
            font-size: 32px;
            font-weight: 700;
            color: #333;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            margin: 20px 0;
            text-align: center;
        }

        .cart-items {
            margin-bottom: 30px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e5e5;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex-grow: 1;
            margin-left: 20px;
        }

        .item-name {
            font-weight: 600;
            font-size: 18px;
        }

        .item-quantity {
            margin-top: 5px;
            display: flex;
            align-items: center;
        }

        .item-quantity button {
            background-color: #f25019;
            color: white;
            border: none;
            padding: 5px;
            border-radius: 50%;
            cursor: pointer;
        }

        .item-quantity input {
            width: 50px;
            text-align: center;
            margin: 0 10px;
            font-size: 16px;
        }

        .item-price {
            font-size: 18px;
            color: #f25019;
            font-weight: bold;
            margin-top: 10px;
        }

        .remove-item {
            background-color: #f25019;
            color: white;
            border: none;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        /* Ringkasan Belanja */
        .order-summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .summary-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .subtotal, .discount, .total {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .total {
            font-size: 22px;
            font-weight: bold;
            color: #f25019;
        }

        .checkout {
            width: 100%;
            padding: 15px;
            background-color: #f25019;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        .checkout:hover {
            background-color: #e24017;
        }

        /* Additional Styles for Consistency */
        .cart-item img {
            width: 100px;
            height: 100px;
        }

        .cart-item .item-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .cart-item .item-price {
            font-size: 16px;
        }

        .order-summary .subtotal, .order-summary .discount, .order-summary .total {
            font-size: 16px;
        }

        .order-summary .total {
            font-size: 20px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="brand">SI Bu Untung</div>
        <div class="title">Keranjang Belanja</div>

        <!-- Tampilan Produk di Keranjang -->
        <div class="cart-items">
            @foreach ($cart as $productId => $qty)
                @php
                    $product = $products->firstWhere('id_barang', $productId);
                @endphp
                <div class="cart-item">
                    <img src="{{ asset('storage/' . $product->gambar_url) }}" alt="{{ $product->nama_barang }}" />
                    <div class="item-details">
                        <div class="item-name">{{ $product->nama_barang }}</div>
                        <div class="item-quantity">
                            <button>-</button>
                            <input type="number" value="{{ $qty }}" min="1" max="999">
                            <button>+</button>
                        </div>
                        <div class="item-price">Rp {{ number_format($product->harga_satuan * $qty, 0, ',', '.') }}</div>
                    </div>
                    <button class="remove-item">Hapus</button>
                </div>
            @endforeach
        </div>

        <!-- Ringkasan Belanja -->
        <div class="order-summary">
            <div class="summary-title">Ringkasan Pesanan</div>
            <div class="subtotal">
                Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}
            </div>
            <div class="discount">
                Diskon: Rp 0
            </div>
            <div class="total">
                Total Belanja: Rp {{ number_format($total, 0, ',', '.') }}
            </div>
            <button class="checkout">Beli Sekarang</button>
        </div>
    </div>
</body>
</html>

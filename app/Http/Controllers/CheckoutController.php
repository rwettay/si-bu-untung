<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Models\AlamatPelanggan;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Transaction;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index(Request $request)
    {
        // CRITICAL: Ensure pelanggan is authenticated
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $cartData = session('cart', []);
        
        if (empty($cartData)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        // Get selected items from POST request or use all items if not provided
        $selectedItems = [];
        if ($request->isMethod('post') && $request->has('selected_items')) {
            $selectedItems = $request->input('selected_items', []);
            // Store selected items in session for later use in store method
            session(['checkout_selected_items' => $selectedItems]);
        } else {
            // If GET request, use all items in cart
            $selectedItems = array_keys($cartData);
            session(['checkout_selected_items' => $selectedItems]);
        }

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu barang untuk dibeli.');
        }

        // Filter cart data to only include selected items
        $filteredCartData = [];
        foreach ($selectedItems as $id_barang) {
            if (isset($cartData[$id_barang])) {
                $filteredCartData[$id_barang] = $cartData[$id_barang];
            }
        }

        if (empty($filteredCartData)) {
            return redirect()->route('cart.index')->with('error', 'Barang yang dipilih tidak ditemukan di keranjang.');
        }

        // Get products from database
        $products = Barang::whereIn('id_barang', array_keys($filteredCartData))->get();
        
        // Calculate totals and prepare cart items
        $subtotal = 0;
        $cartItems = [];
        
        foreach ($filteredCartData as $id_barang => $jumlah_pesanan) {
            $barang = $products->firstWhere('id_barang', $id_barang);
            
            if ($barang) {
                // Validate stock
                if ($jumlah_pesanan > $barang->stok_barang) {
                    return redirect()->route('cart.index')
                        ->with('error', "Stok {$barang->nama_barang} tidak mencukupi. Sisa stok: {$barang->stok_barang}");
                }
                
                $itemTotal = $barang->harga_satuan * $jumlah_pesanan;
                $subtotal += $itemTotal;
                
                $cartItems[] = [
                    'id_barang' => $id_barang,
                    'nama' => $barang->nama_barang,
                    'gambar' => $barang->gambar_url ?? 'https://placehold.co/100x100',
                    'harga' => $barang->harga_satuan,
                    'qty' => $jumlah_pesanan,
                    'subtotal' => $itemTotal,
                ];
            }
        }

        $diskon = 0;
        $total = $subtotal - $diskon;

        // Get pelanggan user data
        $user = Auth::guard('pelanggan')->user();

        // Get alamat pelanggan dari database - ambil SEMUA alamat untuk pelanggan ini
        $alamatList = AlamatPelanggan::where('id_pelanggan', $user->id_pelanggan)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.checkout', [
            'cart'     => collect($cartItems),
            'subtotal' => $subtotal,
            'diskon'   => $diskon,
            'total'    => $total,
            'user'     => $user,
            'alamatList' => $alamatList,
        ]);
    }

    /**
     * Process checkout and create transaction
     */
    public function store(Request $request)
    {
        // CRITICAL: Ensure pelanggan is authenticated
       if (!Auth::guard('pelanggan')->check()) {
    return redirect()->route('welcome')->with('error', 'Silakan login terlebih dahulu.');
}

        $validated = $request->validate([
            'nama_penerima'       => ['required', 'string', 'max:150'],
            'telepon_penerima'    => ['required', 'string', 'max:30'],
            'alamat_pengiriman'   => ['required', 'string', 'max:500'],
            'tanggal_pengiriman'  => ['required', 'date'],
            'waktu_pengiriman'    => ['required', 'string', 'max:20'],
        ], [
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'telepon_penerima.required' => 'Nomor telepon wajib diisi.',
            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',
            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'waktu_pengiriman.required' => 'Waktu pengiriman wajib diisi.',
        ]);

        $cartData = session('cart', []);
        
        if (empty($cartData)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        // Get selected items from session (set during checkout.index)
        $selectedItems = session('checkout_selected_items', array_keys($cartData));
        
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu barang untuk dibeli.');
        }

        // Filter cart data to only include selected items
        $filteredCartData = [];
        foreach ($selectedItems as $id_barang) {
            if (isset($cartData[$id_barang])) {
                $filteredCartData[$id_barang] = $cartData[$id_barang];
            }
        }

        if (empty($filteredCartData)) {
            return redirect()->route('cart.index')->with('error', 'Barang yang dipilih tidak ditemukan di keranjang.');
        }

        DB::beginTransaction();
        try {
            // Get products with lock for stock checking
            $products = Barang::whereIn('id_barang', array_keys($filteredCartData))
                ->lockForUpdate()
                ->get();

            // Validate stock availability
            foreach ($filteredCartData as $id_barang => $jumlah_pesanan) {
                $barang = $products->firstWhere('id_barang', $id_barang);
                
                if (!$barang) {
                    throw new \Exception("Produk dengan ID {$id_barang} tidak ditemukan.");
                }
                
                if ($jumlah_pesanan > $barang->stok_barang) {
                    throw new \Exception("Stok tidak cukup untuk {$barang->nama_barang}. Sisa stok: {$barang->stok_barang}");
                }
            }

            // Calculate total
            $subtotal = 0;
            foreach ($filteredCartData as $id_barang => $jumlah_pesanan) {
                $barang = $products->firstWhere('id_barang', $id_barang);
                $subtotal += $barang->harga_satuan * $jumlah_pesanan;
            }

            $diskon = 0;
            $total = max(0, $subtotal - $diskon);

            // Generate transaction ID
            $idTransaksi = 'TRX' . now()->format('YmdHis') . rand(100, 999);

            // Get pelanggan
            $pelanggan = Auth::guard('pelanggan')->user();

            // Create transaction
            $transaksi = Transaksi::create([
                'id_transaksi'       => $idTransaksi,
                'id_pelanggan'       => $pelanggan->id_pelanggan,
                'tanggal_transaksi'  => now()->toDateString(),
                'tanggal_pengiriman' => $validated['tanggal_pengiriman'],
                'waktu_pengiriman'   => $validated['waktu_pengiriman'],
                'alamat_pengiriman'  => $validated['alamat_pengiriman'],
                'nama_penerima'      => $validated['nama_penerima'],
                'telepon_penerima'   => $validated['telepon_penerima'],
                'status_transaksi'   => 'pending',
                'total_transaksi'    => $total,
                'id_staff'           => null,
            ]);

            // Create transaction details and update stock
            foreach ($filteredCartData as $id_barang => $jumlah_pesanan) {
                $barang = $products->firstWhere('id_barang', $id_barang);
                $itemSubtotal = $barang->harga_satuan * $jumlah_pesanan;

                DetailTransaksi::create([
                    'id_transaksi'   => $idTransaksi,
                    'id_barang'      => $id_barang,
                    'jumlah_pesanan' => $jumlah_pesanan,
                    'subtotal'       => $itemSubtotal,
                ]);

                // Decrease stock and increment sold_count
                $barang->decrement('stok_barang', $jumlah_pesanan);
                $barang->increment('sold_count', $jumlah_pesanan);
                
                // Remove selected items from cart
                unset($cartData[$id_barang]);
            }

            DB::commit();
            
            // Update cart session (only remove selected items, keep unselected ones)
            session(['cart' => $cartData]);
            
            // Update cart count
            $remainingCount = count($cartData);
            session(['cart_unique_count' => $remainingCount]);
            
            // Clear selected items from session
            session()->forget('checkout_selected_items');

            return redirect()->route('checkout.payment', ['id' => $idTransaksi])
                ->with('success', 'Pesanan berhasil dibuat!')
                ->with('order_id', $idTransaksi);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            report($th);
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    /**
     * Show payment page
     */
    public function payment(Request $request, $id)
    {
        // CRITICAL: Ensure pelanggan is authenticated
        if (!Auth::guard('pelanggan')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get transaction
        $transaksi = Transaksi::with(['detailTransaksis.barang', 'pelanggan'])
            ->where('id_transaksi', $id)
            ->first();

        if (!$transaksi) {
            return redirect()->route('customer.home')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Ensure the transaction belongs to the logged-in customer
        $pelanggan = Auth::guard('pelanggan')->user();
        if ($transaksi->id_pelanggan !== $pelanggan->id_pelanggan) {
            return redirect()->route('customer.home')->with('error', 'Akses ditolak.');
        }

        // If already paid, redirect to payment success page
        if ($transaksi->status_transaksi === 'dibayar') {
            return redirect()->route('payment.success', ['order_id' => $id])
                ->with('success', 'Pembayaran sudah dilakukan!')
                ->with('payment_order_id', $id);
        }

        // Configure Midtrans
        $midtransConfig = Config::get('services.midtrans');
        MidtransConfig::$serverKey = $midtransConfig['server_key'];
        MidtransConfig::$isProduction = $midtransConfig['is_production'];
        MidtransConfig::$isSanitized = $midtransConfig['is_sanitized'];
        MidtransConfig::$is3ds = $midtransConfig['is_3ds'];

        // Prepare transaction details for Midtrans
        $transactionDetails = [
            'order_id' => $transaksi->id_transaksi,
            'gross_amount' => (int) $transaksi->total_transaksi,
        ];

        // Prepare item details
        $itemDetails = [];
        foreach ($transaksi->detailTransaksis as $detail) {
            $itemDetails[] = [
                'id' => $detail->id_barang,
                'price' => (int) ($detail->subtotal / $detail->jumlah_pesanan),
                'quantity' => (int) $detail->jumlah_pesanan,
                'name' => $detail->barang->nama_barang,
            ];
        }

        // Prepare customer details
        $customerDetails = [
            'first_name' => $pelanggan->nama_pelanggan ?? $pelanggan->username ?? 'Pelanggan',
            'email' => $pelanggan->email ?? '',
            'phone' => $pelanggan->no_hp ?? $transaksi->telepon_penerima ?? '',
        ];

        // Prepare billing and shipping address
        $billingAddress = [
            'first_name' => $transaksi->nama_penerima ?? $pelanggan->nama_pelanggan ?? 'Pelanggan',
            'phone' => $transaksi->telepon_penerima ?? $pelanggan->no_hp ?? '',
            'address' => $transaksi->alamat_pengiriman ?? '',
            'city' => 'Kebumen',
            'postal_code' => '54300',
            'country_code' => 'IDN',
        ];

        // Prepare Snap parameters
        // Use payment success page as finish callback with order_id as query parameter
        $finishUrl = route('payment.success') . '?order_id=' . $transaksi->id_transaksi;
        $callbackUrl = route('checkout.payment.callback');
        
        // Ensure URLs are absolute (important for localhost)
        if (!filter_var($finishUrl, FILTER_VALIDATE_URL)) {
            $finishUrl = url($finishUrl);
        }
        if (!filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
            $callbackUrl = url($callbackUrl);
        }
        
        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'billing_address' => $billingAddress,
            'shipping_address' => $billingAddress,
            'enabled_payments' => [
                'qris', // Prioritaskan QRIS di urutan pertama
                'gopay',
                'shopeepay',
                'dana',
                'linkaja',
                'ovo',
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'other_va',
                'mandiri_clickpay',
                'cimb_clicks',
                'bca_klikbca',
                'bca_klikpay',
                'bri_epay',
                'telkomsel_cash',
                'echannel',
                'indomaret',
                'alfamart',
                'akulaku',
                'kioson',
            ],
            'callbacks' => [
                'finish' => $finishUrl, // Direct to payment success page
                'unfinish' => $callbackUrl,
                'error' => $callbackUrl,
            ],
            // Add additional parameters to ensure QRIS works
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 1440, // 24 hours
            ],
        ];

        try {
            // Log parameters for debugging (without sensitive data)
            \Log::info('Midtrans Snap Token Request', [
                'order_id' => $transaksi->id_transaksi,
                'gross_amount' => $transactionDetails['gross_amount'],
                'item_count' => count($itemDetails),
                'enabled_payments_count' => count($params['enabled_payments'] ?? []),
                'enabled_payments' => $params['enabled_payments'] ?? [],
                'qris_enabled' => in_array('qris', $params['enabled_payments'] ?? []),
                'finish_url' => $finishUrl,
                'callback_url' => $callbackUrl,
                'is_production' => MidtransConfig::$isProduction,
            ]);

            // Validate required fields before generating token
            if (empty($midtransConfig['server_key']) || empty($midtransConfig['client_key'])) {
                throw new \Exception('Midtrans Server Key atau Client Key belum dikonfigurasi. Silakan periksa file .env');
            }
            
            // Validate transaction amount
            if ($transactionDetails['gross_amount'] <= 0) {
                throw new \Exception('Total transaksi tidak valid.');
            }
            
            // Validate item details
            if (empty($itemDetails)) {
                throw new \Exception('Detail barang tidak ditemukan.');
            }
            
            // Generate Snap Token
            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $snapException) {
                \Log::error('Midtrans Snap Token Generation Exception', [
                    'order_id' => $transaksi->id_transaksi,
                    'error' => $snapException->getMessage(),
                    'code' => $snapException->getCode(),
                    'trace' => $snapException->getTraceAsString()
                ]);
                throw new \Exception('Gagal membuat token pembayaran: ' . $snapException->getMessage());
            }

            if (!$snapToken || empty($snapToken)) {
                \Log::error('Midtrans Snap Token is empty', [
                    'order_id' => $transaksi->id_transaksi,
                    'params_sent' => array_keys($params)
                ]);
                throw new \Exception('Token pembayaran kosong. Silakan periksa konfigurasi Midtrans atau coba lagi beberapa saat.');
            }

            \Log::info('Midtrans Snap Token Generated Successfully', [
                'order_id' => $transaksi->id_transaksi,
                'token_length' => strlen($snapToken)
            ]);

            return view('customer.payment', [
                'transaksi' => $transaksi,
                'snapToken' => $snapToken,
                'clientKey' => $midtransConfig['client_key'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Token Generation Failed', [
                'order_id' => $transaksi->id_transaksi,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = $e->getMessage();
            
            // Translate common error messages to Indonesian
            if (stripos($errorMessage, 'failed to process') !== false || 
                stripos($errorMessage, 'transaction failed') !== false) {
                $errorMessage = 'Gagal memproses transaksi. Silakan coba lagi atau hubungi customer service.';
            } else {
                $errorMessage = 'Gagal memproses pembayaran: ' . $errorMessage . '. Silakan coba lagi atau hubungi customer service.';
            }
            
            return redirect()->route('checkout.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Handle Midtrans payment callback
     */
    public function paymentCallback(Request $request)
    {
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        // Log for debugging
        \Log::info('Payment Callback', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'all_params' => $request->all()
        ]);

        if (!$orderId) {
            return redirect()->route('customer.home')
                ->with('error', 'Invalid payment callback.');
        }

        // Get transaction
        $transaksi = Transaksi::where('id_transaksi', $orderId)->first();

        if (!$transaksi) {
            return redirect()->route('customer.home')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        // Check if already paid - redirect immediately
        if ($transaksi->status_transaksi === 'dibayar') {
            return redirect()->route('payment.success', ['order_id' => $orderId])
                ->with('success', 'Pembayaran berhasil!')
                ->with('payment_order_id', $orderId);
        }

        // Handle different transaction statuses
        // Update status in database first
        $isPaid = false;
        
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // Payment successful
            // For credit card, check fraud_status, but for other payment methods, settlement/capture means paid
            if ($fraudStatus == null || $fraudStatus == 'accept') {
                $transaksi->update([
                    'status_transaksi' => 'dibayar',
                ]);
                $isPaid = true;
                
                \Log::info('Payment Status Updated to dibayar', ['order_id' => $orderId]);
            }
        } elseif ($statusCode == '200' && ($transactionStatus == 'settlement' || $transactionStatus == 'capture')) {
            // Alternative check for successful payment
            if ($fraudStatus == null || $fraudStatus == 'accept') {
                $transaksi->update([
                    'status_transaksi' => 'dibayar',
                ]);
                $isPaid = true;
                
                \Log::info('Payment Status Updated to dibayar (alternative)', ['order_id' => $orderId]);
            }
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Payment failed - keep as pending so user can retry
            // Status remains pending
            \Log::info('Payment Failed', ['order_id' => $orderId, 'status' => $transactionStatus]);
        }
        
        if ($isPaid) {
            // Redirect to payment success page
            return redirect()->route('payment.success', ['order_id' => $orderId])
                ->with('success', 'Pembayaran berhasil!')
                ->with('payment_order_id', $orderId);
        }
        
        // For pending or failed payments, redirect back to payment page
        return redirect()->route('checkout.payment', ['id' => $orderId])
            ->with('info', 'Status pembayaran telah diperbarui.');
    }

    /**
     * Handle Midtrans notification (webhook)
     */
    public function paymentNotification(Request $request)
    {
        // Configure Midtrans
        $midtransConfig = Config::get('services.midtrans');
        MidtransConfig::$serverKey = $midtransConfig['server_key'];
        MidtransConfig::$isProduction = $midtransConfig['is_production'];

        try {
            // Verify notification from Midtrans
            $notificationBody = $request->all();
            
            $orderId = $notificationBody['order_id'] ?? null;
            $transactionStatus = $notificationBody['transaction_status'] ?? null;
            $fraudStatus = $notificationBody['fraud_status'] ?? null;
            $statusCode = $notificationBody['status_code'] ?? null;

            // Log for debugging
            \Log::info('Payment Notification Received', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            if (!$orderId) {
                return response()->json(['status' => 'error', 'message' => 'Order ID not found'], 400);
            }

            // Get transaction
            $transaksi = Transaksi::where('id_transaksi', $orderId)->first();

            if (!$transaksi) {
                \Log::warning('Transaction not found in notification', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            // Skip if already paid
            if ($transaksi->status_transaksi === 'dibayar') {
                \Log::info('Transaction already paid', ['order_id' => $orderId]);
                return response()->json(['status' => 'success', 'message' => 'Already paid']);
            }

            // Update transaction status based on notification
            $isPaid = false;
            
            // Handle successful payment statuses
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // Payment successful - update status directly
                // Note: For credit card, check fraud_status, but for other payment methods, settlement/capture means paid
                if ($fraudStatus == null || $fraudStatus == 'accept') {
                    if ($transaksi->status_transaksi !== 'dibayar') {
                        $transaksi->update([
                            'status_transaksi' => 'dibayar',
                        ]);
                        $isPaid = true;
                        
                        \Log::info('Payment Status Updated to dibayar via Notification', [
                            'order_id' => $orderId,
                            'transaction_status' => $transactionStatus,
                            'fraud_status' => $fraudStatus
                        ]);
                    } else {
                        $isPaid = true;
                    }
                } else {
                    \Log::warning('Payment settlement but fraud_status not accepted', [
                        'order_id' => $orderId,
                        'fraud_status' => $fraudStatus
                    ]);
                }
            } elseif ($statusCode == '200' && ($transactionStatus == 'settlement' || $transactionStatus == 'capture')) {
                // Alternative check for successful payment
                if ($fraudStatus == null || $fraudStatus == 'accept') {
                    if ($transaksi->status_transaksi !== 'dibayar') {
                        $transaksi->update([
                            'status_transaksi' => 'dibayar',
                        ]);
                        $isPaid = true;
                        
                        \Log::info('Payment Status Updated to dibayar via Notification (alternative)', [
                            'order_id' => $orderId,
                            'status_code' => $statusCode,
                            'transaction_status' => $transactionStatus
                        ]);
                    } else {
                        $isPaid = true;
                    }
                }
            } elseif ($transactionStatus == 'pending') {
                // Payment still pending - no update needed
                \Log::info('Payment Still Pending via Notification', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus
                ]);
            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                // Payment failed - keep as pending (don't revert stock automatically)
                // Status remains pending so user can retry payment
                \Log::info('Payment Failed via Notification', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus
                ]);
            } else {
                // Unknown status - log for debugging
                \Log::warning('Unknown Payment Status via Notification', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus,
                    'status_code' => $statusCode,
                    'fraud_status' => $fraudStatus,
                    'all_data' => $notificationBody
                ]);
            }

            return response()->json([
                'status' => 'success',
                'is_paid' => $isPaid,
                'transaction_status' => $transaksi->fresh()->status_transaksi
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment Notification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check payment status (for polling)
     * This method also checks status directly from Midtrans API as fallback
     */
    public function checkPaymentStatus(Request $request, $id)
    {
        // CRITICAL: Ensure pelanggan is authenticated
        if (!Auth::guard('pelanggan')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get transaction
        $transaksi = Transaksi::where('id_transaksi', $id)->first();

        if (!$transaksi) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Ensure the transaction belongs to the logged-in customer
        $pelanggan = Auth::guard('pelanggan')->user();
        if ($transaksi->id_pelanggan !== $pelanggan->id_pelanggan) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // If already paid, return immediately
        if ($transaksi->status_transaksi === 'dibayar') {
            return response()->json([
                'status' => $transaksi->status_transaksi,
                'status_text' => ucfirst($transaksi->status_transaksi),
                'is_paid' => true,
            ]);
        }

        // Check status directly from Midtrans API as fallback (for localhost/webhook issues)
        try {
            // Configure Midtrans
            $midtransConfig = Config::get('services.midtrans');
            MidtransConfig::$serverKey = $midtransConfig['server_key'];
            MidtransConfig::$isProduction = $midtransConfig['is_production'];

            // Get transaction status from Midtrans
            $midtransStatus = Transaction::status($id);
            
            \Log::info('Midtrans API Status Check', [
                'order_id' => $id,
                'midtrans_status' => $midtransStatus->transaction_status ?? 'unknown',
                'status_code' => $midtransStatus->status_code ?? 'unknown'
            ]);

            // Update database if status changed
            $transactionStatus = $midtransStatus->transaction_status ?? null;
            $fraudStatus = $midtransStatus->fraud_status ?? null;
            $statusCode = $midtransStatus->status_code ?? null;

            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // Payment successful
                if ($fraudStatus == null || $fraudStatus == 'accept') {
                    if ($transaksi->status_transaksi !== 'dibayar') {
                        $transaksi->update([
                            'status_transaksi' => 'dibayar',
                        ]);
                        
                        \Log::info('Payment Status Updated to dibayar via API Check', [
                            'order_id' => $id,
                            'transaction_status' => $transactionStatus
                        ]);
                    }
                }
            } elseif ($statusCode == '200' && ($transactionStatus == 'settlement' || $transactionStatus == 'capture')) {
                // Alternative check
                if ($fraudStatus == null || $fraudStatus == 'accept') {
                    if ($transaksi->status_transaksi !== 'dibayar') {
                        $transaksi->update([
                            'status_transaksi' => 'dibayar',
                        ]);
                        
                        \Log::info('Payment Status Updated to dibayar via API Check (alternative)', [
                            'order_id' => $id,
                            'status_code' => $statusCode
                        ]);
                    }
                }
            }

            // Refresh transaction from database
            $transaksi->refresh();

        } catch (\Exception $e) {
            // If API check fails, just use database status
            \Log::warning('Midtrans API Status Check Failed', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => $transaksi->status_transaksi,
            'status_text' => $transaksi->status_transaksi === 'pending' ? 'Menunggu Pembayaran' : ucfirst($transaksi->status_transaksi),
            'is_paid' => $transaksi->status_transaksi === 'dibayar',
        ]);
    }

    /**
     * Show checkout success page
     */
    public function success()
    {
        if (!session('success')) {
            return redirect()->route('customer.home');
        }

        return view('customer.checkout-success');
    }

    /**
     * Show payment success page
     */
    public function paymentSuccess(Request $request)
    {
        // CRITICAL: Ensure pelanggan is authenticated
        if (!Auth::guard('pelanggan')->check()) {
            \Log::warning('Payment Success: User not authenticated');
            return redirect()->route('customer.home')->with('error', 'Silakan login terlebih dahulu.');
        }

        $orderId = $request->query('order_id') ?? $request->input('order_id') ?? session('payment_order_id');
        
        \Log::info('Payment Success Page Accessed', [
            'order_id' => $orderId,
            'query_order_id' => $request->query('order_id'),
            'input_order_id' => $request->input('order_id'),
            'session_order_id' => session('payment_order_id')
        ]);
        
        if (!$orderId) {
            \Log::warning('Payment Success: Order ID not found');
            return redirect()->route('customer.home')->with('error', 'Order ID tidak ditemukan.');
        }

        // Get transaction
        $transaksi = Transaksi::where('id_transaksi', $orderId)->first();

        if (!$transaksi) {
            \Log::warning('Payment Success: Transaction not found', ['order_id' => $orderId]);
            return redirect()->route('customer.home')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Ensure the transaction belongs to the logged-in customer
        $pelanggan = Auth::guard('pelanggan')->user();
        if ($transaksi->id_pelanggan !== $pelanggan->id_pelanggan) {
            \Log::warning('Payment Success: Access denied', [
                'order_id' => $orderId,
                'transaksi_pelanggan' => $transaksi->id_pelanggan,
                'logged_pelanggan' => $pelanggan->id_pelanggan
            ]);
            return redirect()->route('customer.home')->with('error', 'Akses ditolak.');
        }

        // Log current status
        \Log::info('Payment Success: Transaction status check', [
            'order_id' => $orderId,
            'current_status' => $transaksi->status_transaksi,
            'expected_status' => 'dibayar'
        ]);

        // Get payment method from Midtrans if available
        $paymentMethod = '—';
        try {
            $midtransConfig = Config::get('services.midtrans');
            MidtransConfig::$serverKey = $midtransConfig['server_key'];
            MidtransConfig::$isProduction = $midtransConfig['is_production'];
            
            $midtransStatus = Transaction::status($orderId);
            if (isset($midtransStatus->payment_type)) {
                $paymentType = $midtransStatus->payment_type;
                // Format payment method name
                $paymentMethodMap = [
                    'qris' => 'QRIS',
                    'gopay' => 'GoPay',
                    'bank_transfer' => 'Bank Transfer',
                    'credit_card' => 'Kartu Kredit',
                    'echannel' => 'Mandiri Bill',
                    'cstore' => 'Indomaret/Alfamart',
                ];
                $paymentMethod = $paymentMethodMap[$paymentType] ?? ucfirst(str_replace('_', ' ', $paymentType));
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to get payment method from Midtrans', ['error' => $e->getMessage()]);
        }

        // Get user name
        $userName = $pelanggan->nama_pelanggan ?? 'Pelanggan';

        // Calculate delivery estimate (1x24 hours from transaction date)
        $deliveryEstimate = $transaksi->tanggal_pengiriman 
            ? \Carbon\Carbon::parse($transaksi->tanggal_pengiriman)->format('d M Y')
            : \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->addDay()->format('d M Y');

        // Verify that payment is actually successful
        // For QRIS and some payment methods, status might still be pending when user is redirected
        // So we'll allow access if status is 'dibayar' OR if we have order_id (user came from payment)
        if ($transaksi->status_transaksi !== 'dibayar') {
            // Check if this is a recent transaction (within last 5 minutes) - might still be processing
            $transactionAge = now()->diffInMinutes($transaksi->created_at);
            
            if ($transactionAge > 5) {
                // Transaction is old and still not paid - redirect back to payment page
                \Log::info('Payment Success: Transaction not paid and too old', [
                    'order_id' => $orderId,
                    'status' => $transaksi->status_transaksi,
                    'age_minutes' => $transactionAge
                ]);
                return redirect()->route('checkout.payment', ['id' => $orderId])
                    ->with('info', 'Pembayaran belum selesai. Silakan selesaikan pembayaran Anda.');
            } else {
                // Recent transaction - might still be processing, show success page anyway
                \Log::info('Payment Success: Transaction pending but recent, showing success page', [
                    'order_id' => $orderId,
                    'status' => $transaksi->status_transaksi,
                    'age_minutes' => $transactionAge
                ]);
            }
        } else {
            \Log::info('Payment Success: Transaction is paid, showing success page', ['order_id' => $orderId]);
        }

        // Clear session
        session()->forget('payment_order_id');

        return view('customer.paymentsuccess', [
            'order_id' => $orderId,
            'transaksi' => $transaksi,
            'total' => $transaksi->total_transaksi,
            'payment_method' => $paymentMethod,
            'transaction_date' => $transaksi->tanggal_transaksi,
            'transaction_time' => $transaksi->created_at ?? now(),
            'delivery_estimate' => $deliveryEstimate,
            'user_name' => $userName,
        ]);
    }
}
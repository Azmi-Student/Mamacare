<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction; // [BARU] Wajib import ini untuk cek status

class DonationController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function pay(Request $request)
    {
        // 1. Buat Order ID Unik
        // Format: DONASI-{TIMESTAMP}-{USER_ID}
        $orderId = 'DONASI-' . time() . '-' . auth()->id();

        // 2. Data Transaksi
        $amount = $request->input('amount', 50000); 

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name, 
                'email' => auth()->user()->email,
            ],
            'item_details' => [
                [
                    'id' => 'donasi1',
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Dukungan untuk MamaCare'
                ]
            ]
        ];

        try {
            // 3. Ambil Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            // [UPDATE] Return JSON sekarang mengirimkan 'order_id' juga
            // Ini penting agar Frontend (JS) tahu ID mana yang harus dicek statusnya
            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $orderId 
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * [BARU] Fungsi untuk Cek Status Pembayaran (Polling)
     * Dipanggil oleh JavaScript setiap 5 detik
     */
    public function checkStatus($orderId)
    {
        try {
            // Tanya ke Midtrans: "Order ID ini statusnya apa?"
            $status = Transaction::status($orderId);
            
            // Kembalikan statusnya ke Frontend
            // Contoh status: 'settlement' (Lunas), 'pending', 'expire', 'cancel'
            return response()->json([
                'status' => $status->transaction_status
            ]);

        } catch (\Exception $e) {
            // Jika order ID belum ada di Midtrans (misal baru banget dibuat), anggap pending/error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
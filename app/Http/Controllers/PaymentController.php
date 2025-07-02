<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;


class PaymentController extends Controller
{
    // [Lihat semua status pembayaran]
    public function index(Request $request)
    {
        $query = Payment::with('order');

        // Filter optional
        if ($request->has('status_bayar') && $request->status_bayar) {
            $query->where('status_bayar', $request->status_bayar);
        }
        if ($request->has('metode_bayar') && $request->metode_bayar) {
            $query->where('metode_bayar', $request->metode_bayar);
        }

        $payments = $query->orderByDesc('id')->get();

        return view('admin.payments.index', compact('payments'));
    }

    // [Tandai manual "lunas"]
    public function setLunas($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status_bayar = 'success';
        $payment->waktu_bayar = now();
        $payment->save();

        // Update order status juga
        if ($payment->order) {
            $payment->order->status_order = 'lunas';
            $payment->order->save();
        }

        return back()->with('success', 'Pembayaran ditandai sebagai lunas.');
    }

    public function createTransaction(Request $request)
{
    // Inisialisasi konfigurasi Midtrans
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = config('midtrans.is_sanitized');
    Config::$is3ds = config('midtrans.is_3ds');

    // Data transaksi (bisa disesuaikan)
    $params = [
        'transaction_details' => [
            'order_id' => 'ORDER-' . time(),
            'gross_amount' => $request->amount ?? 100000, // nominal transaksi
        ],
        'customer_details' => [
            'first_name' => $request->first_name ?? 'User',
            'email' => $request->email ?? 'user@example.com',
            'phone' => $request->phone ?? '081234567890',
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function handleCallback(Request $request)
{
    $serverKey = config('midtrans.server_key');

    $signatureKey = hash('sha512',
        $request->order_id .
        $request->status_code .
        $request->gross_amount .
        $serverKey
    );

    if ($signatureKey !== $request->signature_key) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    // Ambil ID dari order_id Midtrans (contoh: INV-123-ABCDE)
    $orderId = explode('-', $request->order_id)[1] ?? null;

    if (!$orderId) {
        return response()->json(['message' => 'Invalid order_id'], 400);
    }

    $order = Order::find($orderId);
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    switch ($request->transaction_status) {
        case 'capture':
        case 'settlement':
            $order->status_order = 'dibayar';
            break;

        case 'pending':
            $order->status_order = 'pending';
            break;

        case 'deny':
        case 'cancel':
        case 'expire':
            $order->status_order = 'gagal';
            break;
    }

    $order->save();

    return response()->json(['message' => 'Callback handled']);
}
}
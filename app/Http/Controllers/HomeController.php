<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    public function katalog(Request $request)
    {
        $kategori = $request->kategori; // id kategori dari dropdown

        // Ambil semua kategori untuk dropdown filter
        $categories = \App\Models\Category::orderBy('nama_kategori')->get();

        // Query produk, filter jika ada kategori dipilih
        $products = Product::with('images')
            ->when($kategori, function ($q) use ($kategori) {
                $q->where('category_id', $kategori);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('home.katalog', compact('products', 'categories', 'kategori'));
    }

    public function keranjang()
    {
        $keranjang = session()->get('keranjang', []);
        return view('home.keranjang', compact('keranjang'));
    }

    public function tambahKeranjang(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $keranjang = session()->get('keranjang', []);
        $keranjang[$id] = [
            'id' => $product->id,
            'nama' => $product->nama_produk,
            'harga' => $product->harga_jual,
            'qty' => ($keranjang[$id]['qty'] ?? 0) + 1,
        ];
        session()->put('keranjang', $keranjang);
        // ===> Redirect ke halaman keranjang
        return redirect()->route('home.keranjang')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function hapusKeranjang($id)
    {
        $keranjang = session()->get('keranjang', []);
        unset($keranjang[$id]);
        session()->put('keranjang', $keranjang);
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function checkout(Request $request)
{
    $keranjang = session()->get('keranjang', []);
    if (empty($keranjang)) return back()->with('error', 'Keranjang kosong.');

    $total = array_sum(array_map(fn($i) => $i['qty'] * $i['harga'], $keranjang));
    $tipe_order = Auth::user()->role === 'mitra' ? 'mitra' : 'customer';

    $orderPrefix = strtoupper(Str::random(5)) . '-' . time();
    $midtransOrderId = 'INV-' . $orderPrefix;

    $order = Order::create([
        'user_id' => Auth::id(),
        'tipe_order' => $tipe_order,
        'tanggal_order' => now(),
        'total_order' => $total,
        'status_order' => 'pending',
        'alamat_kirim' => $request->alamat ?? Auth::user()->alamat,
        'midtrans_order_id' => $midtransOrderId,
    ]);

    foreach ($keranjang as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'qty' => $item['qty'],
            'harga' => $item['harga'],
            'subtotal' => $item['qty'] * $item['harga'],
        ]);
    }

    // Midtrans
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    try {
        $payload = [
            'transaction_details' => [
                'order_id' => $order->midtrans_order_id,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payments' => ['bank_transfer', 'gopay', 'qris'],
        ];

        $snapToken = Snap::getSnapToken($payload);

    Payment::create([
        'order_id'          => $order->id,
        'midtrans_order_id' => $order->midtrans_order_id,
        'jumlah_bayar'      => $total,
        'metode_bayar'      => 'midtrans',
        'status_bayar'      => 'pending',
    ]);

        session()->forget('keranjang');

        return view('home.snap_checkout', [
            'snapToken' => $snapToken,
            'order' => $order,
        ]);
    } catch (\Exception $e) {
        Log::error('[Checkout Error] ' . $e->getMessage());
        return redirect()->route('home.myorders.index')->with('error', 'Gagal membuat Snap Token: ' . $e->getMessage());
    }
}


    public function pesananSaya()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
        return view('home.orders', compact('orders'));
    }

    public function pesananDetail($id)
    {
        $order = \App\Models\Order::with('items.product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        return view('home.order_detail', compact('order'));
    }
}
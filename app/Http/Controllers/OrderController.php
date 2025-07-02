<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    // Lihat semua transaksi/order
    public function index()
    {
        // Relasi ke user, orderBy terbaru
        $orders = Order::with('user')->orderByDesc('created_at')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Form buat transaksi baru (kasir offline)
    public function create()
    {
        $products = Product::all();
        // Ambil semua user dengan role customer atau mitra
        $customers = User::whereIn('role', ['customer', 'mitra'])->get();
        return view('admin.orders.create', compact('products', 'customers'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',      // user_id = customer/mitra
            'tipe_order'  => 'required|in:offline,online,mitra',
            'produk_id'   => 'required|array',
            'produk_id.*' => 'required|exists:products,id',
            'qty'         => 'required|array',
            'qty.*'       => 'required|numeric|min:1',
        ]);

        // Buat order baru
        $order = Order::create([
            'user_id'       => $request->user_id,
            'tipe_order'    => $request->tipe_order,
            'tanggal_order' => now()->toDateString(),
            'total_order'   => 0,
            'status_order'  => 'pending',
            'alamat_kirim'  => $request->alamat_kirim ?? null,
            'catatan'       => $request->catatan ?? null,
        ]);

        // Simpan detail produk & qty
        $total = 0;
        foreach ($request->produk_id as $i => $pid) {
            $qty = $request->qty[$i];
            $product = Product::findOrFail($pid);
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $pid,
                'qty'        => $qty,
                'harga_jual' => $product->harga_jual,
                'subtotal'   => $product->harga_jual * $qty,
            ]);
            $total += $product->harga_jual * $qty;
        }
        $order->update(['total_order' => $total]);
        return redirect()->route('orders.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    // Detail transaksi/order
    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Proses pembayaran (tunai/qris, update status)
    public function pay(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status_order = 'lunas';
        // $order->metode_bayar = $request->input('metode', 'tunai'); // tambahkan jika ada kolom metode_bayar
        $order->save();
        return back()->with('success', 'Pembayaran berhasil!');
    }

    // Tandai kirim barang
    public function kirim($id)
    {
        $order = Order::findOrFail($id);
        $order->status_order = 'selesai';
        $order->save();
        return back()->with('success', 'Pesanan sudah dikirim!');
    }

    // Ubah status order manual dari admin
    public function ubahStatus(Request $request, $id)
    {
        $request->validate([
            'status_order' => 'required|in:pending,lunas,dikirim,selesai',
        ]);
        $order = Order::findOrFail($id);
        $order->status_order = $request->status_order;
        $order->save();
        return back()->with('success', 'Status pesanan berhasil diubah!');
    }
}

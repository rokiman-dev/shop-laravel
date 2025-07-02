<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class SupplierController extends Controller
{
    // Lihat semua supplier
    public function index()
    {
        $suppliers = User::where('role', 'supplier')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    // Verifikasi supplier baru (is_active = 0)
    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = 1;
        $user->save();
        return back()->with('success', 'Supplier diaktifkan!');
    }

    // Lihat produk milik supplier
    public function products($supplier_id)
    {
        $supplier = User::findOrFail($supplier_id);
        $products = Product::where('supplier_id', $supplier_id)->get();
        return view('admin.suppliers.products', compact('supplier', 'products'));
    }

    // Pesan produk dari supplier (buat purchase dan clone produk ke toko)
    public function orderProduct(Request $request, $supplier_id, $product_id)
    {
        $supplier = User::findOrFail($supplier_id);
        $produk = Product::findOrFail($product_id);

        // Simulasi input: jumlah pesan dari form
        $qty = $request->input('qty', 1);

        // 1. Buat record pembelian (purchases)
        $purchase = Purchase::create([
            'supplier_id' => $supplier_id,
            'tanggal'     => now(),
            'status'      => 'pending', // bisa ubah sesuai kebutuhan
            'total'       => $produk->harga_beli * $qty,
        ]);

        // 2. Buat detail item pembelian (purchase_items)
        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id'  => $produk->id,
            'qty'         => $qty,
            'harga'       => $produk->harga_beli,
            'subtotal'    => $produk->harga_beli * $qty,
        ]);

        // 3. Clone produk ke produk toko (supplier_id = NULL)
        $cloned = $produk->replicate();
        $cloned->supplier_id = null;
        $cloned->stok = $qty; // stok sesuai qty pembelian
        $cloned->save();

        // Clone images jika ada
        foreach ($produk->images as $img) {
            $cloned->images()->create([
                'file_path' => $img->file_path
            ]);
        }

        return back()->with('success', 'Produk berhasil dipesan & masuk ke produk toko.');
    }
}

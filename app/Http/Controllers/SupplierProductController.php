<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SupplierProductController extends Controller
{
    // 1) List produk milik supplier
    public function index()
    {
        $products = Product::where('supplier_id', auth()->id())
            ->with('category')
            ->get();
        return view('supplier.products.index', compact('products'));
    }

    // 2) Lihat produk toko yang tidak punya supplier
    public function produkToko()
    {
        $products = Product::with('category')
            ->whereNull('supplier_id')
            ->get();
        return view('supplier.products.index', compact('products'));
    }

    // 3) Form tawarkan alternatif produk
    public function tawarkanAlternatifForm($id)
    {
        $product = Product::with('category')->whereNull('supplier_id')->findOrFail($id);
        return view('supplier.products.tawarkan', compact('product'));
    }

    // 4) Simpan produk alternatif
    public function tawarkanAlternatif(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric',
            'deskripsi'   => 'nullable',
        ]);

        $productToko = Product::whereNull('supplier_id')->findOrFail($id);

        Product::create([
            'category_id' => $productToko->category_id,
            'kode_produk' => $request->nama_produk, // opsional: ganti sesuai kebutuhan
            'nama_produk' => $request->nama_produk,
            'harga_jual'  => $request->harga_jual,
            'stok'        => $request->stok,
            'deskripsi'   => $request->deskripsi,
            'supplier_id' => auth()->id(),
        ]);

        return redirect()->route('supplier.products.toko')->with('success', 'Alternatif produk berhasil diajukan!');
    }
}

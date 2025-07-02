<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->has('supplier_id')) $query->where('supplier_id', $request->supplier_id);
        if ($request->has('category_id')) $query->where('category_id', $request->category_id);
        $products = $query->with('category', 'supplier')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = \App\Models\User::where('role', 'supplier')->get();
        return view('admin.products.create', compact('categories', 'suppliers'));
    }


    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'category_id' => 'required',
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'harga_beli'  => 'nullable|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric',
            'deskripsi'   => 'nullable'
        ]);
        $data = $request->only('category_id', 'kode_produk', 'nama_produk', 'harga_beli', 'harga_jual', 'stok', 'deskripsi');
        $data['supplier_id'] = ($user->role == 'supplier') ? $user->id : $request->supplier_id;
        $product = Product::create($data);

        // Upload multi foto produk
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $product->images()->create([
                    'file_path' => $img->store('product_images', 'public')
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk ditambah!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = \App\Models\User::where('role', 'supplier')->get();
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();
        $request->validate([
            'category_id' => 'required',
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'harga_beli'  => 'nullable|numeric',
            'harga_jual'  => 'required|numeric',
            'stok'        => 'required|numeric',
            'deskripsi'   => 'nullable'
        ]);

        if ($user->role === 'admin' || ($user->role === 'supplier' && $product->supplier_id == $user->id)) {
            $product->update($request->only('category_id', 'kode_produk', 'nama_produk', 'harga_beli', 'harga_jual', 'stok', 'deskripsi'));

            // Upload foto baru jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $product->images()->create([
                        'file_path' => $img->store('product_images', 'public')
                    ]);
                }
            }
            return redirect()->route('products.index')->with('success', 'Produk diupdate!');
        }
        abort(403, 'Akses ditolak!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $user = auth()->user();
        if ($user->role === 'admin' || ($user->role === 'supplier' && $product->supplier_id == $user->id)) {
            $product->delete();
            return back()->with('success', 'Produk dihapus!');
        }
        abort(403, 'Akses ditolak!');
    }
}

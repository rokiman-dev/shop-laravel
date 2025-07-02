@extends('layouts.supplier')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center">
                    <i class="bx bx-share-alt bx-sm me-2"></i>
                    <h5 class="mb-0">Tawarkan Alternatif untuk: <span class="text-primary">{{ $product->nama_produk }}</span></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('supplier.products.tawarkan.store', $product->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Produk Alternatif</label>
                            <input type="text" name="nama_produk" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success">
                                <i class="bx bx-send"></i> Kirim Alternatif
                            </button>
                            <a href="{{ route('supplier.products.toko') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
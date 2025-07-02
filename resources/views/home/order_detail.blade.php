@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-primary d-flex align-items-center">
                <i class="bx bx-receipt me-2" style="font-size:1.3rem"></i>
                <span class="fw-semibold text-white">Detail Pesanan #{{ $order->id }}</span>
            </div>
            <div class="card-body pb-2">
                <div class="row mb-2">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Tanggal:</strong>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d/m/Y H:i') }}</span>
                            </li>
                            <li>
                                <strong>Status:</strong>
                                <span class="badge rounded-pill bg-{{ 
                                    $order->status_order == 'lunas' ? 'success' : 
                                    ($order->status_order == 'pending' ? 'warning text-dark' : 'secondary')
                                }}">
                                    <i class="bx bx-{{ $order->status_order == 'lunas' ? 'check-circle' : ($order->status_order == 'pending' ? 'time-five' : 'info-circle') }}"></i>
                                    {{ ucfirst($order->status_order) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Alamat Kirim:</strong>
                                <span class="text-muted">{{ $order->alamat_kirim }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless align-middle mb-2">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->nama_produk }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga,0,',','.') }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-end fw-semibold">Rp{{ number_format($item->subtotal,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td class="text-end fw-bold text-primary">
                                    Rp{{ number_format($order->total_order,0,',','.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <a href="{{ route('home.myorders.index') }}" class="btn btn-outline-secondary mt-3">
                    <i class="bx bx-arrow-back"></i> Kembali ke Pesanan Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
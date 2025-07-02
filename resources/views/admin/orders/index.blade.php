@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaksi Penjualan</h5>
                <a href="{{ route('orders.create') }}" class="btn btn-success">
                    <i class="bx bx-plus"></i> Transaksi Baru (Kasir)
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->user ? $order->user->name : '-' }}</td>
                                <td>Rp{{ number_format($order->total_order ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                    $status = strtolower($order->status_order);
                                    @endphp
                                    @if($status == 'selesai' || $status == 'lunas')
                                    <span class="badge bg-label-success">{{ ucfirst($order->status_order) }}</span>
                                    @elseif($status == 'pending')
                                    <span class="badge bg-label-warning">{{ ucfirst($order->status_order) }}</span>
                                    @else
                                    <span class="badge bg-label-secondary">{{ ucfirst($order->status_order) ?? '-' }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                        <i class="bx bx-detail"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @if($orders->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada transaksi penjualan.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
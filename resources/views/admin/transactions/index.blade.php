@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaksi Penjualan</h5>
                <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Transaksi
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>No Invoice</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th style="width:160px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $i => $trx)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $trx->nomor_invoice }}</td>
                                <td>{{ $trx->tanggal }}</td>
                                <td>{{ $trx->customer }}</td>
                                <td>Rp{{ number_format($trx->total,0,',','.') }}</td>
                                <td>
                                    @if($trx->status == 'selesai')
                                    <span class="badge bg-label-success">Selesai</span>
                                    @else
                                    <span class="badge bg-label-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $trx->id) }}" class="btn btn-info btn-sm">
                                        <i class="bx bx-detail"></i> Detail
                                    </a>
                                    <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($transactions->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada transaksi penjualan.</td>
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
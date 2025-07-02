@extends('layouts.admin')
@section('content')
<div class="row mb-4">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="mb-0"><i class="bx bx-bar-chart-alt"></i> Laporan Penjualan</h5>
            </div>
            <div class="card-body pt-3">
                <form method="GET" class="row g-2 align-items-end mb-4">
                    <div class="col-md-4">
                        <label for="tgl_awal" class="form-label mb-0">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" value="{{ $tglAwal }}">
                    </div>
                    <div class="col-md-4">
                        <label for="tgl_akhir" class="form-label mb-0">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2 mt-3 mt-md-0">
                        <button class="btn btn-primary flex-fill">
                            <i class="bx bx-search"></i> Filter
                        </button>
                        <a href="{{ route('laporan.penjualan_pdf', ['tgl_awal'=>$tglAwal,'tgl_akhir'=>$tglAkhir]) }}"
                            class="btn btn-danger flex-fill" target="_blank">
                            <i class="bx bxs-file-pdf"></i> PDF
                        </a>
                    </div>
                </form>
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>No Order</th>
                                <th>Customer</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $o)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $o->id }}</td>
                                <td>{{ $o->user->name ?? '-' }}</td>
                                <td>Rp{{ number_format($o->total_order,0,',','.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-active fw-bold">
                                <td colspan="3" class="text-end">Total</td>
                                <td class="text-success">Rp{{ number_format($total,0,',','.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
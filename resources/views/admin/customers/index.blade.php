@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Customer</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th style="width:150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $i => $c)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $c->name }}</td>
                                <td>{{ $c->email }}</td>
                                <td>
                                    @if($c->is_active)
                                    <span class="badge bg-label-success me-1">Aktif</span>
                                    @else
                                    <span class="badge bg-label-warning me-1">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customers.show', $c->id) }}" class="btn btn-info btn-sm">
                                        <i class="bx bx-user"></i> Lihat Profil
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @if($customers->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada customer.</td>
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
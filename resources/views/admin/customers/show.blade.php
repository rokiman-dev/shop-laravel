@extends('layouts.admin')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card shadow">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        @if($customer->foto)
                        <img src="{{ asset('storage/' . $customer->foto) }}" alt="Foto" class="rounded-circle border" width="70" height="70">
                        @else
                        <span class="avatar-initial rounded-circle bg-label-info text-uppercase d-flex align-items-center justify-content-center" style="width:70px; height:70px; font-size:2rem;">
                            <i class="bx bx-user"></i>
                        </span>
                        @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">{{ $customer->name }}</h4>
                        <span class="badge bg-{{ $customer->is_active ? 'success' : 'warning' }}">
                            {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                <hr>
                <ul class="list-unstyled mb-3">
                    <li class="mb-2"><i class="bx bx-envelope me-2"></i><strong>Email:</strong> {{ $customer->email }}</li>
                    @if($customer->no_hp)
                    <li class="mb-2"><i class="bx bx-phone me-2"></i><strong>No HP:</strong> {{ $customer->no_hp }}</li>
                    @endif
                    @if($customer->alamat)
                    <li class="mb-2"><i class="bx bx-map me-2"></i><strong>Alamat:</strong> {{ $customer->alamat }}</li>
                    @endif
                </ul>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
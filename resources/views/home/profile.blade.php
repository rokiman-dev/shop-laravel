@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow mb-4 border-0">
            <div class="card-header bg-primary d-flex align-items-center">
                <i class="bx bx-user-circle me-2" style="font-size: 1.5rem"></i>
                <span class="text-white fw-semibold">Profil Saya</span>
            </div>
            <div class="card-body pb-3">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" class="rounded-circle shadow-sm" width="90" height="90" alt="Avatar">
                    <div class="fw-bold mt-2" style="font-size: 1.1rem;">
                        {{ auth()->user()->name }}
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-end">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control-plaintext" readonly value="{{ auth()->user()->email }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-end">Nomor HP</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control-plaintext" readonly value="{{ auth()->user()->no_hp ?? '-' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label text-end">Alamat</label>
                    <div class="col-sm-8">
                        <textarea class="form-control-plaintext" rows="2" readonly>{{ auth()->user()->alamat ?? '-' }}</textarea>
                    </div>
                </div>
                <div class="row mb-4">
                    <label class="col-sm-4 col-form-label text-end">Role</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control-plaintext" readonly value="{{ ucfirst(auth()->user()->role) }}">
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('home.katalog') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
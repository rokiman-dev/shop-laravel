@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bx bx-edit-alt me-2"></i>Edit Data Mitra</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('mitras.update', $mitra->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $mitra->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $mitra->email }}" required>
                    </div>
                    <button class="btn btn-success"><i class="bx bx-save me-1"></i>Update</button>
                    <a href="{{ route('mitras.index') }}" class="btn btn-secondary"><i class="bx bx-arrow-back"></i> Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
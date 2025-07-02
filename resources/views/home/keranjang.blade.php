@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="bx bx-cart me-2"></i>
                <span class="fw-semibold">Keranjang Belanja</span>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(count($keranjang) > 0)
                <div class="table-responsive mb-4">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($keranjang as $item)
                            @php $subtotal = $item['harga'] * $item['qty']; $total += $subtotal; @endphp
                            <tr>
                                <td>{{ $item['nama'] }}</td>
                                <td class="text-end">Rp{{ number_format($item['harga'],0,',','.') }}</td>
                                <td class="text-center">{{ $item['qty'] }}</td>
                                <td class="text-end">Rp{{ number_format($subtotal,0,',','.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('home.keranjang.hapus', $item['id']) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('POST')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bx bx-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary fw-bold">
                                <td colspan="3" class="text-end">Total</td>
                                <td colspan="2" class="text-end text-primary">Rp{{ number_format($total,0,',','.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <form action="{{ route('home.checkout') }}" method="POST" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Alamat Pengiriman <small class="text-muted">(opsional)</small></label>
                        <textarea name="alamat" class="form-control" rows="2"
                            placeholder="Kosongkan untuk pakai alamat profil"></textarea>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" id="pay-button" class="btn btn-success">
                            <i class="bx bx-credit-card"></i> Checkout & Bayar
                        </button>
                        <a href="{{ route('home.katalog') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali Belanja
                        </a>
                    </div>
                </form>
                @else
                <div class="alert alert-warning text-center">
                    <i class="bx bx-cart-alt fs-3"></i><br>
                    Keranjang Anda kosong. Yuk, belanja dulu!
                </div>
                <div class="text-center">
                    <a href="{{ route('home.katalog') }}" class="btn btn-primary">
                        <i class="bx bx-store"></i> Ke Katalog Produk
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
document.getElementById('pay-button').addEventListener('click', function() {
    fetch("{{ route('home.checkout') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                alamat: document.querySelector('[name=alamat]').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.token) {
                snap.pay(data.token, {
                    onSuccess: function(result) {
                        window.location.href = "/checkout/success";
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran...");
                    },
                    onError: function(result) {
                        alert("Terjadi kesalahan saat pembayaran.");
                    }
                });
            } else {
                alert("Gagal membuat transaksi Midtrans.");
            }
        });
});
</script>
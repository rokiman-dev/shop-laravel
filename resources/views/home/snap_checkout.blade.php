@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <h4>Mohon tunggu, membuka halaman pembayaran...</h4>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
</script>
<script>
snap.pay('{{ $snapToken }}', {
    onSuccess: function(result) {
        window.location.href = "{{ route('home.checkout.success') }}"
    },
    onPending: function(result) {
        window.location.href = "{{ route('home.checkout.success') }}"
    },
    onError: function(result) {
        alert("Pembayaran gagal: " + result.status_message);
        window.location.href = "{{ route('home.myorders.index') }}";
    },
    onClose: function() {
        alert('Transaksi belum selesai.');
        window.location.href = "{{ route('home.myorders.index') }}"
    }
});
</script>
@endsection
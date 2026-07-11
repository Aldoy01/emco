@extends('layouts.public')
@section('title', 'Konfirmasi Pembayaran ' . $order->invoice_number)
@section('content')
<section class="page-title"><p class="eyebrow">Payment Confirmation</p><h1>Konfirmasi Pembayaran</h1><p>{{ $order->invoice_number }} - Total {{ $order->formatted_total }}</p></section>
<section class="section narrow">
    <form class="rfq-form checkout-form" method="post" enctype="multipart/form-data" action="{{ route('checkout.confirm.store', $order) }}">
        @csrf
        <div class="form-grid"><label>Nama Pengirim<input name="payer_name" value="{{ old('payer_name', $order->customer_name) }}" required></label><label>Bank Pengirim<input name="bank_name" value="{{ old('bank_name') }}" required></label><label>Tanggal Transfer<input type="date" name="transfer_date" value="{{ old('transfer_date', now()->format('Y-m-d')) }}" required></label><label>Nominal Transfer<input type="number" name="amount" value="{{ old('amount', $order->total_idr) }}" required></label></div>
        <label>Upload Bukti Pembayaran<input type="file" name="payment_proof" accept="image/jpeg,image/png,image/webp,application/pdf"></label>
        <small>Format JPG, PNG, WEBP, atau PDF. Maksimal 3 MB.</small>
        @if($errors->any())<div class="alert error">Mohon periksa kembali data konfirmasi.</div>@endif
        <button class="btn btn-gold" type="submit">Kirim Konfirmasi Pembayaran</button>
    </form>
</section>
@endsection

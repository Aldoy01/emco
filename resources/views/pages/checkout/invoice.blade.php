@extends('layouts.public')
@section('title', 'Invoice ' . $order->invoice_number)
@section('content')
@php($hideCommercial = config('emko.hide_commercial_values'))
<section class="page-title"><p class="eyebrow">Invoice</p><h1>{{ $order->invoice_number }}</h1><p>Invoice berhasil dibuat. Instruksi pembayaran dan konfirmasi pembayaran tersedia di bawah.</p></section>
<section class="section invoice-layout">
    <div class="invoice-paper">
        @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        <div class="invoice-head"><div><strong>EMKO / Gencontrol Indonesia</strong><p>Sales invoice & payment instruction</p></div><span class="status-pill status-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span></div>
        <div class="invoice-grid">
            <div><span>Customer</span><strong>{{ $order->customer_name }}</strong><p>{{ $order->company ?: '-' }}<br>{{ $order->email }}<br>{{ $order->phone }}</p></div>
            <div><span>Alamat Pengiriman</span><p>{{ $order->shipping_address }}</p></div>
        </div>
        <div class="table-wrap"><table><thead><tr><th>Produk</th><th>Qty</th><th>Harga Unit</th><th>Subtotal</th></tr></thead><tbody><tr><td>{{ $order->product->product_name }}</td><td>{{ $hideCommercial ? '' : $order->quantity }}</td><td>{{ $order->formatted_unit_price }}</td><td>{{ $order->formatted_subtotal }}</td></tr><tr><td colspan="3">Ongkir / adjustment</td><td>{{ $order->formatted_shipping_cost }}</td></tr><tr><td colspan="3"><strong>Total Invoice</strong></td><td><strong>{{ $order->formatted_total }}</strong></td></tr></tbody></table></div>
        <div class="payment-box"><h2>Instruksi Pembayaran</h2><p>Transfer ke salah satu rekening berikut, lalu lakukan konfirmasi pembayaran.</p><div class="bank-grid"><div><strong>BCA</strong><span>1234567890</span><small>PT Gencontrol Indonesia</small></div><div><strong>Mandiri</strong><span>9876543210</span><small>PT Gencontrol Indonesia</small></div></div><p>Berita transfer: <strong>{{ $order->invoice_number }}</strong></p></div>
        <div class="invoice-actions"><a class="btn btn-gold" href="{{ route('checkout.confirm', $order) }}">Konfirmasi Pembayaran</a><a class="btn btn-outline" href="{{ route('products.show', $order->product) }}">Kembali ke Produk</a></div>
    </div>
    <aside class="notification-panel"><h2>Notifikasi Order</h2><p>Invoice dibuat dan siap dikirim ke email customer. Untuk produksi, SMTP bisnis bisa diaktifkan agar invoice terkirim otomatis.</p><div class="notice-step active"><strong>1. Invoice dibuat</strong><span>{{ $order->created_at->format('d M Y H:i') }}</span></div><div class="notice-step {{ $order->status !== 'pending_payment' ? 'active' : '' }}"><strong>2. Konfirmasi pembayaran</strong><span>{{ $order->paid_at ? $order->paid_at->format('d M Y H:i') : 'Menunggu customer' }}</span></div><div class="notice-step"><strong>3. Verifikasi & pengiriman</strong><span>Sales/admin follow-up</span></div></aside>
</section>
@endsection

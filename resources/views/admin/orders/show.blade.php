@extends('layouts.admin')
@section('title', 'Periksa ' . $order->invoice_number)
@section('page_title', 'Detail Pembelian')
@section('content')
@php($hideCommercial = config('emko.hide_commercial_values'))
<section class="crm-hero-panel small">
    <div>
        <p class="crm-kicker">Invoice {{ $order->invoice_number }}</p>
        <h2>{{ $order->customer_name }} · {{ $order->formatted_total }}</h2>
    </div>
    <span class="status-pill status-{{ $order->status }}">{{ $statuses[$order->status] ?? $order->status }}</span>
</section>

<section class="admin-order-detail">
    <div class="admin-panel crm-card">
        <div class="section-head compact"><div><p class="crm-kicker">Order</p><h2>Rincian Invoice</h2></div></div>
        <div class="order-detail-grid">
            <div><span>Customer</span><strong>{{ $order->customer_name }}</strong><p>{{ $order->company ?: '-' }}<br>{{ $order->email }}<br>{{ $order->phone }}</p></div>
            <div><span>Alamat Pengiriman</span><p>{!! nl2br(e($order->shipping_address)) !!}</p></div>
            <div><span>Produk</span><strong>{{ $order->product->product_name }}</strong><p>{{ $hideCommercial ? '' : $order->quantity }} {{ $hideCommercial ? '' : '×' }} {{ $order->formatted_unit_price }}</p></div>
            <div><span>Komposisi Invoice</span><p>Subtotal: {{ $order->formatted_subtotal }}<br>Diskon: {{ $order->formatted_discount }}<br>{{ $order->tax_label }}: {{ $order->formatted_tax }}<br>Pengiriman: {{ $order->formatted_shipping_cost }}</p></div>
            <div><span>Total Invoice</span><strong class="order-total">{{ $order->formatted_total }}</strong><p>Dibuat {{ $order->created_at->format('d M Y H:i') }}</p></div>
        </div>

        <div class="payment-review">
            <div>
                <span>Bukti Pembayaran</span>
                @if($order->payment_proof)
                    <a class="btn btn-outline" href="{{ route('admin.orders.proof', $order) }}" target="_blank">Buka Bukti Transfer</a>
                @else
                    <p>Customer belum mengunggah bukti pembayaran.</p>
                @endif
            </div>
            <div>
                <span>Waktu Konfirmasi</span>
                <strong>{{ $order->paid_at ? $order->paid_at->format('d M Y H:i') : '-' }}</strong>
            </div>
        </div>

        @if($order->notes)
            <div class="order-notes"><span>Catatan & Riwayat</span><p>{!! nl2br(e($order->notes)) !!}</p></div>
        @endif
    </div>

    <form class="admin-panel crm-card rfq-form" method="post" action="{{ route('admin.orders.update', $order) }}">
        @csrf
        @method('PUT')
        <div class="section-head compact"><div><p class="crm-kicker">Verification</p><h2>Update Status</h2></div></div>
        <label>Status Pembelian
            <select name="status" required>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(old('status', $order->status) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label>Catatan Admin
            <textarea name="admin_notes" rows="7" placeholder="Contoh: pembayaran sesuai, siap diproses, nomor resi, atau alasan penolakan">{{ old('admin_notes') }}</textarea>
        </label>
        @if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
        <button class="btn btn-gold" type="submit">Simpan Status</button>
        <a class="btn btn-outline" href="{{ route('admin.orders.index') }}">Kembali ke Daftar</a>
    </form>
</section>
@endsection

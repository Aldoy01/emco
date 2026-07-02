@extends('layouts.admin')
@section('title', 'Pembelian & Pembayaran')
@section('page_title', 'Pembelian & Pembayaran')
@section('content')
@php($hideCommercial = config('emko.hide_commercial_values'))
<section class="crm-hero-panel small">
    <div>
        <p class="crm-kicker">Invoice & payment inbox</p>
        <h2>Periksa invoice, konfirmasi pembayaran, bukti transfer, dan progres pengiriman.</h2>
    </div>
    <div class="order-summary-pills">
        <span>{{ $confirmationCount }} konfirmasi masuk</span>
        <span>{{ $pendingCount }} menunggu bayar</span>
    </div>
</section>

<section class="admin-panel crm-card">
    <form class="admin-order-filter" method="get" action="{{ route('admin.orders.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari invoice, nama, email, atau telepon">
        <select name="status">
            <option value="">Semua status</option>
            @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-gold" type="submit">Filter</button>
    </form>

    <div class="table-wrap crm-table-wrap">
        <table class="crm-table">
            <thead>
                <tr><th>Invoice</th><th>Customer</th><th>Produk</th><th>Total</th><th>Status</th><th>Bukti</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->invoice_number }}</strong><br><small>{{ $order->created_at->format('d M Y H:i') }}</small></td>
                    <td><strong>{{ $order->customer_name }}</strong><br><small>{{ $order->email }} · {{ $order->phone }}</small></td>
                    <td>{{ $order->product->product_name }}<br><small>Qty {{ $hideCommercial ? '' : $order->quantity }}</small></td>
                    <td><strong>{{ $order->formatted_total }}</strong></td>
                    <td><span class="status-pill status-{{ $order->status }}">{{ $statuses[$order->status] ?? $order->status }}</span></td>
                    <td>
                        @if($order->payment_proof)
                            <a class="proof-link" href="{{ route('admin.orders.proof', $order) }}" target="_blank">Lihat Bukti</a>
                        @else
                            <span class="payment-empty">Belum ada</span>
                        @endif
                    </td>
                    <td><a class="soft-link" href="{{ route('admin.orders.show', $order) }}">Periksa</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-cell">Belum ada invoice sesuai filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
<div class="crm-pagination">{{ $orders->links() }}</div>
@endsection

@extends('layouts.public')
@section('title','Dashboard Member EMKO')
@section('content')
<section class="page-title member-title">
    <p class="eyebrow">Member Area</p>
    <h1>Dashboard Member</h1>
    <p>Kelola invoice, status pembayaran, dan riwayat pembelian produk EMKO.</p>
</section>

<section class="section member-dashboard">
    <div class="member-welcome">
        <div>
            <p class="eyebrow">Akun Pembeli</p>
            <h2>Halo, {{ auth()->user()->name }}</h2>
            <p>{{ auth()->user()->email }}</p>
        </div>
        <div class="hero-actions">
            <a class="btn btn-gold" href="{{ route('products.index') }}">Belanja Produk</a>
            <a class="btn btn-outline" href="{{ route('quotation.create') }}">Hubungi Sales</a>
        </div>
    </div>

    <div class="member-metrics">
        <article><span>Total Invoice</span><strong>{{ $totalOrders }}</strong></article>
        <article><span>Menunggu Bayar</span><strong>{{ $pendingOrders }}</strong></article>
        <article><span>Konfirmasi Terkirim</span><strong>{{ $paidOrders }}</strong></article>
    </div>

    <section class="member-panel">
        <div class="section-head compact"><div><p class="eyebrow">Invoices</p><h2>Riwayat Pembelian</h2></div></div>
        <div class="table-wrap crm-table-wrap">
            <table class="crm-table member-table">
                <thead><tr><th>Invoice</th><th>Produk</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->invoice_number }}</strong></td>
                        <td>{{ $order->product->product_name }}<br><small>Qty {{ $order->quantity }}</small></td>
                        <td>{{ $order->formatted_total }}</td>
                        <td><span class="status-pill status-{{ $order->status }}">{{ str_replace('_',' ', $order->status) }}</span></td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td><a class="table-action detail" href="{{ route('checkout.invoice', $order) }}">Lihat Invoice</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-cell">Belum ada invoice. Silakan pilih produk dan checkout.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $orders->links() }}</div>
    </section>
</section>
@endsection
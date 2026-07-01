@extends('layouts.admin')
@section('title', 'Dashboard CRM EMKO')
@section('page_title', 'Sales Dashboard')
@section('content')
<section class="crm-hero-panel">
    <div>
        <p class="crm-kicker">Lead generation snapshot</p>
        <h2>Monitor Hubungi Sales, produk aktif, dan peluang quotation dari satu layar.</h2>
    </div>
    <a class="btn btn-gold" href="{{ route('admin.quotations.index') }}">Kelola Lead</a>
</section>

<div class="metric-grid crm-metrics">
    <article class="metric-card accent-teal"><span>Total Produk</span><strong>{{ $productCount }}</strong><small>Katalog dan pricelist aktif</small></article>
    <article class="metric-card accent-gold"><span>Lead Baru</span><strong>{{ $newRfqs }}</strong><small>Menunggu follow-up sales</small></article>
    <article class="metric-card accent-blue"><span>Lead Open</span><strong>{{ $openRfqs }}</strong><small>Belum closed won/lost</small></article>
    <article class="metric-card accent-green"><span>Conversion</span><strong>{{ $conversionRate }}%</strong><small>Closed won dari total lead</small></article>
</div>

<section class="crm-grid-2">
    <div class="admin-panel crm-card">
        <div class="section-head compact"><div><p class="crm-kicker">Pipeline</p><h2>Status Lead</h2></div><span class="soft-pill">{{ $totalRfqs }} total</span></div>
        <div class="pipeline-list">
            @foreach($pipeline as $stage)
                @php($width = $totalRfqs > 0 ? max(8, round(($stage['total'] / max($totalRfqs, 1)) * 100)) : 8)
                <div class="pipeline-row">
                    <div><strong>{{ $stage['label'] }}</strong><span>{{ $stage['total'] }} lead</span></div>
                    <div class="pipeline-track"><i style="width: {{ $width }}%"></i></div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="admin-panel crm-card chart-card">
        <div class="section-head compact"><div><p class="crm-kicker">Visual</p><h2>Sales Funnel</h2></div></div>
        <div class="bar-chart">
            @foreach($pipeline as $stage)
                @php($height = $totalRfqs > 0 ? max(18, round(($stage['total'] / max($totalRfqs, 1)) * 150)) : 18)
                <div class="bar-item"><span style="height: {{ $height }}px"></span><small>{{ \Illuminate\Support\Str::of($stage['label'])->before(' ') }}</small></div>
            @endforeach
        </div>
    </div>
</section>

<section class="admin-panel crm-card">
    <div class="section-head compact"><div><p class="crm-kicker">Inbox</p><h2>Lead Terbaru</h2></div><a class="soft-link" href="{{ route('admin.quotations.index') }}">Lihat semua</a></div>
    <div class="table-wrap crm-table-wrap">
        <table class="crm-table">
            <thead><tr><th>Customer</th><th>Produk</th><th>Qty</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
            @forelse($recentRfqs as $rfq)
                <tr>
                    <td><strong>{{ $rfq->lead->company }}</strong><br><small>{{ $rfq->lead->name }} Â· {{ $rfq->lead->phone }}</small></td>
                    <td>{{ $rfq->product->product_name }}</td>
                    <td><span class="qty-pill">{{ $rfq->quantity }}</span></td>
                    <td><span class="status-pill status-{{ $rfq->status }}">{{ \App\Models\QuotationRequest::STATUSES[$rfq->status] ?? $rfq->status }}</span></td>
                    <td>{{ $rfq->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty-cell">Belum ada lead masuk.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

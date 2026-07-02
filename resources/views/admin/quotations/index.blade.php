@extends('layouts.admin')
@section('title', 'Sales Leads - CRM EMKO')
@section('page_title', 'Sales Leads')
@section('content')
@php($hideCommercial = config('emko.hide_commercial_values'))
<section class="crm-hero-panel small">
    <div><p class="crm-kicker">Lead inbox</p><h2>Kelola permintaan penawaran, status follow-up, dan export data sales.</h2></div>
    <a class="btn btn-outline" href="{{ route('admin.quotations.export') }}">Export CSV</a>
</section>
<section class="admin-panel crm-card">
    <div class="table-wrap crm-table-wrap">
        <table class="crm-table">
            <thead><tr><th>Customer</th><th>Produk</th><th>Qty</th><th>Lokasi</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($quotations as $rfq)
                <tr>
                    <td><strong>{{ $rfq->lead->company }}</strong><br><small>{{ $rfq->lead->name }} · {{ $rfq->lead->phone }}</small></td>
                    <td>{{ $rfq->product->product_name }}</td>
                    <td><span class="qty-pill">{{ $hideCommercial ? '' : $rfq->quantity }}</span></td>
                    <td>{{ $rfq->project_location }}</td>
                    <td><span class="status-pill status-{{ $rfq->status }}">{{ $statuses[$rfq->status] ?? $rfq->status }}</span></td>
                    <td><a class="soft-link" href="{{ route('admin.quotations.show',$rfq) }}">Detail</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
<div class="crm-pagination">{{ $quotations->links() }}</div>
@endsection

@extends('layouts.admin')
@section('title', 'Produk & Harga - CRM EMKO')
@section('page_title', 'Produk & Harga')
@section('content')
<section class="crm-hero-panel small">
    <div><p class="crm-kicker">Product management</p><h2>Kelola katalog teknikal, gambar produk, harga Rupiah, diskon, dan status produk.</h2></div>
    <a class="btn btn-gold" href="{{ route('admin.products.create') }}">Tambah Produk</a>
</section>
<section class="admin-panel crm-card">
    <div class="table-wrap crm-table-wrap">
        <table class="crm-table">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Harga Dasar</th><th>Harga Diskon</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        <div class="product-cell">
                            <div class="admin-product-thumb has-image"><img src="{{ $product->image ? asset($product->image) : asset('uploads/products/default-catalog.png') }}" alt="{{ $product->product_name }}"></div>
                            <div><strong>{{ $product->product_name }}</strong><br><small>{{ $product->short_description }}</small></div>
                        </div>
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ 'Rp ' . number_format($product->price_idr, 0, ',', '.') }}</td>
                    <td><strong>{{ 'Rp ' . number_format($product->final_price_idr, 0, ',', '.') }}</strong></td>
                    <td><span class="status-pill status-{{ $product->status }}">{{ str_replace('_', ' ', ucfirst($product->status)) }}</span></td>
                    <td><a class="soft-link" href="{{ route('admin.products.edit',$product) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
<div class="crm-pagination">{{ $products->links() }}</div>
@endsection

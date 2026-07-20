@extends('layouts.public')
@section('title',($categoryPage->name ?? 'Katalog Produk').' - EMKO')
@section('content')
<section class="page-title product-catalog-title">
    <p class="eyebrow">Product Catalogue</p>
    <h1>{{ $categoryPage->name ?? 'Katalog Produk EMKO' }}</h1>
    <p>{{ $categoryPage->description ?? 'Pilih produk controller, ATS, synchronizing, monitoring, dan battery charger dengan harga estimasi Rupiah setelah diskon.' }}</p>
</section>

<section class="section product-catalog-section">
    <form class="filter-bar catalog-filter" method="get" action="{{ route('products.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari product code atau nama produk">
        <select name="category">
            <option value="">Semua kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->slug }}" @selected($selectedCategory === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-gold" type="submit">Filter</button>
    </form>

    <div class="catalog-summary-row">
        <div>
            <p class="eyebrow">EMKO Product List</p>
            <h2>Produk tersedia berdasarkan harga tertinggi</h2>
        </div>
        <span>{{ $products->total() }} produk</span>
    </div>

    <div class="product-grid catalog-product-grid">
        @forelse($products as $product)
            @include('components.product-card',['product'=>$product])
        @empty
            <div class="empty-catalog-state">
                <strong>Produk tidak ditemukan.</strong>
                <p>Coba ubah kata kunci atau pilih semua kategori.</p>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrap">{{ $products->links() }}</div>
</section>
@endsection
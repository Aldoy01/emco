@extends('layouts.public')
@section('title','EMKO Gencontrol Indonesia - Generator Controller & ATS')
@section('content')
<section class="hero home-hero" @if(!empty($homeContent['hero_image'])) style="background-image: linear-gradient(120deg,rgba(16,36,63,.92),rgba(22,122,127,.66)), url('{{ asset($homeContent['hero_image']) }}')" @endif>
    <div>
        <p class="eyebrow">{{ $homeContent['hero_eyebrow'] }}</p>
        <h1>{{ $homeContent['hero_title'] }}</h1>
        <p>{{ $homeContent['hero_subtitle'] }}</p>
        <div class="hero-actions">
            <a class="btn btn-gold" href="{{ route('products.index') }}">{{ $homeContent['hero_primary_label'] }}</a>
            <a class="btn btn-light" href="{{ route('quotation.create') }}">{{ $homeContent['hero_secondary_label'] }}</a>
        </div>
    </div>
    <div class="hero-panel home-hero-panel">
        <span>{{ $homeContent['quick_title'] }}</span>
        <ol>@foreach($homeContent['quick_items'] ?? [] as $item)<li>{{ $item }}</li>@endforeach</ol>
        <a class="btn btn-outline" href="{{ route('pricelist') }}">Buka Pricelist</a>
    </div>
</section>

<section class="section home-intro">
    <div class="section-head"><div><p class="eyebrow">Product Range</p><h2>Kategori Produk</h2></div><a href="{{ route('products.index') }}">Semua produk</a></div>
    <div class="category-grid home-category-grid">
        @foreach($categories as $category)
            <a class="category-tile home-category-card" href="{{ route('categories.show',$category) }}">
                <div class="category-card-top">
                    <span class="category-mark">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                    <span class="category-count">{{ $category->products_count }} Produk</span>
                </div>
                <strong>{{ $category->name }}</strong>
                <p>{{ $category->description }}</p>
                <span class="category-link">Lihat kategori</span>
            </a>
        @endforeach
    </div>
</section>

<section class="section muted home-featured">
    <div class="section-head"><div><p class="eyebrow">Featured Products</p><h2>Produk Unggulan</h2></div><a href="{{ route('pricelist') }}">Lihat pricelist</a></div>
    <div class="product-grid">@foreach($featuredProducts as $product) @include('components.product-card',['product'=>$product,'showPrice'=>true]) @endforeach</div>
</section>

<section class="section home-benefits">
    <div class="section-head"><div><p class="eyebrow">Why EMKO</p><h2>{{ $homeContent['benefit_heading'] }}</h2></div></div>
    <div class="benefit-grid home-benefit-cards">
        @foreach($homeContent['benefits'] ?? [] as $index => $benefit)
            <article>
                <div class="benefit-icon">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                <span class="benefit-label">EMKO Advantage</span>
                <strong>{{ $benefit['title'] ?? '' }}</strong>
                <p>{{ $benefit['body'] ?? '' }}</p>
            </article>
        @endforeach
    </div>
</section>

<section class="section muted home-flow">
    <div class="section-head"><div><p class="eyebrow">How It Works</p><h2>{{ $homeContent['flow_heading'] }}</h2></div></div>
    <div class="flow-grid">@foreach($homeContent['flows'] ?? [] as $index => $flow)<article><span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span><strong>{{ $flow['title'] ?? '' }}</strong><p>{{ $flow['body'] ?? '' }}</p></article>@endforeach</div>
</section>

<section class="section split home-cta-section">
    <div><p class="eyebrow">{{ $homeContent['cta_eyebrow'] }}</p><h2>{{ $homeContent['cta_title'] }}</h2><p>{{ $homeContent['cta_text'] }}</p></div>
    <div class="home-cta-card"><strong>Sales EMKO</strong><p>Email: <a href="mailto:{{ config('emko.sales_email') }}">{{ config('emko.sales_email') }}</a></p><p>WhatsApp: {{ config('emko.sales_whatsapp') }}</p><div class="hero-actions"><a class="btn btn-gold" href="{{ route('quotation.create') }}">Hubungi Sales</a><a class="btn btn-outline" href="{{ route('contact') }}">Kontak</a></div></div>
</section>
@endsection

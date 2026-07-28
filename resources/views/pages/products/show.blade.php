@extends('layouts.public')
@section('title',$product->product_name.' - EMKO')
@section('content')
@php
    $productWaText = rawurlencode("Halo Sales EMKO, saya ingin konsultasi produk:\n\nProduk: {$product->product_name}\nHarga estimasi: {$product->formatted_final_price_idr}\nQty:\nNama:\nPerusahaan:\nLokasi proyek:\nKebutuhan teknis:");
    $productWaLink = 'https://wa.me/' . config('emko.sales_whatsapp') . '?text=' . $productWaText;
    $productFaqs = collect($product->product_faqs ?? [])->filter(fn ($faq) => !empty($faq['question']) || !empty($faq['answer']))->values();
    if ($productFaqs->isEmpty()) {
        $productFaqs = collect([
            ['question' => 'Apakah produk ini ready stock?', 'answer' => 'Ketersediaan stok mengikuti kondisi gudang dan perlu dikonfirmasi oleh tim sales sebelum pemesanan diproses.'],
            ['question' => 'Apakah harga sudah termasuk pajak dan pengiriman?', 'answer' => 'Harga estimasi belum termasuk pajak, shipping, instalasi, konfigurasi, dan biaya lain sesuai kebutuhan proyek.'],
            ['question' => 'Apakah bisa konsultasi spesifikasi sebelum order?', 'answer' => 'Bisa. Tim sales dapat membantu menyesuaikan produk dengan kebutuhan genset, panel, ATS, AMF, synchronizing, dan monitoring.'],
        ]);
    }
@endphp
<section class="detail-hero product-detail-hero">
    <div>
        <p class="eyebrow">{{ $product->category->name }}</p>
        <h1>{{ $product->product_name }}</h1>
        <span class="product-status detail-status status-{{ $product->status }}">{{ $product->status_label }}</span>
        <p>{{ $product->short_description }}</p>
        <div class="hero-actions">@if($product->is_purchasable)<a class="btn btn-gold" href="{{ route('checkout.create',$product) }}">Order</a>@else<span class="btn btn-outline disabled-action">{{ $product->status === 'by_request' ? 'Pembelian via Sales' : 'Tidak Tersedia' }}</span>@endif<a class="btn btn-light" href="{{ route('quotation.create',['product'=>$product->id]) }}">Hubungi Sales</a><a class="btn btn-light" href="{{ $productWaLink }}">WhatsApp Sales</a></div>
    </div>
    <aside class="product-detail-media has-image"><img src="{{ $product->image ? asset($product->image) : asset('uploads/products/default-catalog.png') }}" alt="{{ $product->product_name }}"></aside>
    <aside class="quote-box price-card-modern">
        <div class="price-card-top">
            <span class="price-card-kicker">Harga Promo</span>
            <span class="product-status status-{{ $product->status }}">{{ $product->status_label }}</span>
        </div>
        <div class="price-card-main">
            <strong class="price-value">{{ $product->formatted_final_price_idr }}</strong>
            <div class="price-line"><span>Harga dasar</span><del>{{ $product->formatted_price_idr }}</del></div>
            <div class="price-line"><span>Diskon</span><strong>{{ number_format($product->discount_percent,0) }}%</strong></div>
        </div>
        @if($product->purchase_information)
            <p class="purchase-info">{{ $product->purchase_information }}</p>
        @endif
        @if($product->price_note)
            <small class="price-note">{{ $product->price_note }}</small>
        @endif
        <div class="price-card-actions">
            @if($product->is_purchasable)
                <a class="btn btn-gold price-buy" href="{{ route('checkout.create',$product) }}">Order</a>
            @else
                <span class="btn btn-outline disabled-action price-buy">{{ $product->status === 'by_request' ? 'Beli via Sales' : 'Tidak Tersedia' }}</span>
            @endif
            <a class="btn btn-outline price-sales" href="{{ route('quotation.create',['product'=>$product->id]) }}">Sales</a>
        </div>
    </aside>
</section>

<section class="section split top-align product-tech-sales-section">
    <div>
        <h2>Fitur Utama</h2>
        <ul class="feature-list">@foreach($product->features ?? [] as $feature)<li>{{ $feature }}</li>@endforeach</ul>
    </div>
    <div>
        <h2>Spesifikasi</h2>
        <ul class="spec-list">@foreach($product->specifications ?? [] as $spec)<li>{{ $spec }}</li>@endforeach</ul>
        <a class="btn btn-outline" href="{{ $product->datasheet_file ?: route('downloads') }}">Download Datasheet</a>
    </div>
    <aside class="faq-help-card specs-sales-card">
        <span class="faq-help-icon" aria-hidden="true"></span>
        <p class="eyebrow">Butuh jawaban cepat?</p>
        <h3>Konsultasikan spesifikasi produk ini.</h3>
        <p>Tim sales bantu cek stok, konfigurasi teknis, jadwal pengiriman, invoice, dan rekomendasi unit sesuai kebutuhan panel atau genset.</p>
        <div class="faq-help-actions">
            <a class="btn btn-gold" href="{{ $productWaLink }}">WhatsApp Sales</a>
            <a class="btn btn-outline" href="{{ route('quotation.create', ['product' => $product->id]) }}">Minta Penawaran</a>
            @if($product->is_purchasable)
                <a class="btn btn-soft" href="{{ route('checkout.create', $product) }}">Order Produk</a>
            @endif
        </div>
    </aside>
</section>

@if($comparisonGroups->isNotEmpty())
<section class="section comparison-section">
    <div class="section-head">
        <div>
            <p class="eyebrow">Product Comparison</p>
            <h2>Comparison Table Generator Controller</h2>
        </div>
        <a href="{{ route('quotation.create', ['product' => $product->id]) }}">Hubungi Sales</a>
    </div>

    @foreach($comparisonGroups as $group)
        <div class="comparison-group">
            <h3>{{ $group['title'] }}</h3>
            <div class="comparison-wrap">
                <table class="comparison-table">
                    <thead>
                        <tr class="comparison-images">
                            <th class="feature-head"></th>
                            @foreach($group['columns'] as $column)
                                @php($item = $column['product'])
                                <th class="{{ optional($item)->id === $product->id ? 'active-column' : '' }}">
                                    <div class="comparison-product-image has-image"><img src="{{ $item && $item->image ? asset($item->image) : asset('uploads/products/default-catalog.png') }}" alt="{{ $column['label'] }}"></div>
                                </th>
                            @endforeach
                        </tr>
                        <tr class="comparison-names">
                            <th class="feature-head"></th>
                            @foreach($group['columns'] as $column)
                                @php($item = $column['product'])
                                <th class="{{ optional($item)->id === $product->id ? 'active-column' : '' }}">
                                    @if($item)
                                        <a href="{{ route('products.show', $item) }}">{{ $column['label'] }}</a>
                                    @else
                                        {{ $column['label'] }}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['rows'] as [$label, $values])
                            <tr>
                                <th>{{ $label }}</th>
                                @foreach($values as $index => $value)
                                    @php($item = $group['columns'][$index]['product'] ?? null)
                                    <td class="{{ optional($item)->id === $product->id ? 'active-column' : '' }}">{!! $value !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="comparison-actions-row">
                            <th>Pilih Produk</th>
                            @foreach($group['columns'] as $column)
                                @php($item = $column['product'])
                                <td class="{{ optional($item)->id === $product->id ? 'active-column' : '' }}">
                                    @if($item)
                                        <div class="comparison-actions">
                                            @if($item->is_purchasable)
                                                <a class="btn btn-gold" href="{{ route('checkout.create', $item) }}">Beli</a>
                                            @else
                                                <span class="btn btn-outline disabled-action">{{ $item->status_label }}</span>
                                            @endif
                                            <a class="btn btn-outline" href="{{ route('quotation.create', ['product' => $item->id]) }}">Hubungi Sales</a>
                                        </div>
                                    @else
                                        <span class="comparison-muted">By request</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endforeach
</section>
@endif

@if($related->count())<section class="section muted related-products-section"><div class="section-head"><h2>Produk Terkait</h2></div><div class="product-grid catalog-product-grid related-product-grid">@foreach($related as $item) @include('components.product-card',['product'=>$item]) @endforeach</div></section>@endif
@if($productFaqs->isNotEmpty())
<section class="section product-faq-section">
    <div class="section-head faq-modern-head faq-centered-head">
        <div>
            <p class="eyebrow">Product FAQ</p>
            <h2>Pertanyaan umum sebelum order</h2>
            <p>Ringkasan jawaban cepat sebelum Anda konsultasi spesifikasi, ketersediaan stok, dan kebutuhan proyek.</p>
        </div>
    </div>
    <div class="product-faq-layout faq-only-layout">
        <div class="product-faq-list">
            @foreach($productFaqs as $faq)
                @if(!empty($faq['question']) || !empty($faq['answer']))
                    <details class="product-faq-item" @if($loop->first) open @endif>
                        <summary><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $faq['question'] ?: 'Informasi Produk' }}</summary>
                        <div class="faq-answer">
                            <p>{{ $faq['answer'] ?: '-' }}</p>
                        </div>
                    </details>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@php($productImage = $product->image ? asset($product->image) : asset('uploads/products/default-catalog.png'))
@php($displayBasePrice = $product->formatted_price_idr)
@php($displayFinalPrice = $product->formatted_final_price_idr)
<article class="product-card">
    <a class="product-visual has-image" href="{{ route('products.show',$product) }}" aria-label="Lihat detail {{ $product->product_name }}">
        <img src="{{ $productImage }}" alt="{{ $product->product_name }}">
    </a>
    <div class="product-body">
        <div class="product-card-meta">
            <div class="badge">{{ $product->category->name }}</div>
            <span class="product-status status-{{ $product->status }}">{{ $product->status_label }}</span>
        </div>
        <h3><a href="{{ route('products.show',$product) }}">{{ $product->product_name }}</a></h3>
        <p>{{ $product->short_description }}</p>
        <div class="promo-price">
            <div class="promo-top">
                <span class="old-price"><b>Harga Dasar</b> <del>{{ $displayBasePrice }}</del></span>
                <span class="save-badge">{{ $displayBasePrice && $displayFinalPrice ? 'Hemat ' . number_format($product->discount_percent,0) . '%' : '' }}</span>
            </div>
            <div class="final-price"><span>Harga Diskon</span><strong>{{ $displayFinalPrice }}</strong></div>
        </div>
        <div class="card-actions catalog-actions">
            @if($product->is_purchasable)
                <a class="catalog-action catalog-buy" href="{{ route('products.show',$product) }}"><span>Beli</span></a>
            @else
                <span class="catalog-action catalog-disabled"><span>{{ $product->status === 'by_request' ? 'Request' : 'Tidak tersedia' }}</span></span>
            @endif
            <a class="catalog-action catalog-consult" href="{{ route('quotation.create',['product'=>$product->id]) }}"><span>Sales</span></a>
        </div>
    </div>
</article>

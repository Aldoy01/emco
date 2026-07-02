@extends('layouts.admin')
@section('title', $product->exists ? 'Edit Produk' : 'Tambah Produk')
@section('page_title', $product->exists ? 'Edit Produk' : 'Tambah Produk')
@section('content')
@php($hideCommercial = config('emko.hide_commercial_values'))
<form class="rfq-form admin-panel crm-card" method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update',$product) : route('admin.products.store') }}">
    @csrf
    @if($product->exists) @method('PUT') @endif

    <div class="product-form-layout">
        <div class="form-grid">
            <label>Kategori
                <select name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id',$product->category_id)==$category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Product Code<input name="product_code" value="{{ old('product_code',$product->product_code) }}" required></label>
            <label>Nama Produk<input name="product_name" value="{{ old('product_name',$product->product_name) }}" required></label>
            <label>Status
                <select name="status">
                    <option value="active" @selected(old('status',$product->status)=='active')>Active</option>
                    <option value="by_request" @selected(old('status',$product->status)=='by_request')>By Request</option>
                    <option value="inactive" @selected(old('status',$product->status)=='inactive')>Inactive</option>
                    <option value="discontinued" @selected(old('status',$product->status)=='discontinued')>Discontinued</option>
                </select>
            </label>
            <label>Harga Dasar (nilai pricelist)<input id="priceUsdInput" type="number" step="0.01" name="price_usd" value="{{ $hideCommercial ? old('price_usd') : old('price_usd',$product->price_usd) }}" required></label>
            <label>Discount %<input id="discountInput" type="number" step="0.01" name="discount_percent" value="{{ $hideCommercial ? old('discount_percent') : old('discount_percent',$product->discount_percent ?: 5) }}" required></label>
            <label>Harga Diskon (otomatis)<input id="finalPriceInput" type="number" step="0.01" name="final_price_usd" value="{{ $hideCommercial ? old('final_price_usd') : old('final_price_usd',$product->final_price_usd) }}" readonly><small>Terisi otomatis dari Harga Dasar dikurangi Discount %.</small></label>
            <label>Datasheet URL<input name="datasheet_file" value="{{ old('datasheet_file',$product->datasheet_file) }}"></label>
        </div>

        <aside class="image-upload-panel">
            <div class="image-preview-box">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}">
                @else
                    <span>{{ strtoupper(substr($product->product_name ?: 'EMKO', 0, 2)) }}</span>
                @endif
            </div>
            <label>Upload Gambar Produk
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
            </label>
            <small>Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>
            @error('image')<div class="alert error">{{ $message }}</div>@enderror
        </aside>
    </div>

    <label>Deskripsi Singkat<textarea name="short_description">{{ old('short_description',$product->short_description) }}</textarea></label>
    <label>Fitur, satu baris per item<textarea name="features_text">{{ old('features_text',implode("\n",$product->features ?? [])) }}</textarea></label>
    <label>Spesifikasi, satu baris per item<textarea name="specifications_text">{{ old('specifications_text',implode("\n",$product->specifications ?? [])) }}</textarea></label>
    <label>Catatan Harga<input name="price_note" value="{{ old('price_note',$product->price_note) }}"></label>
    <label class="check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$product->is_featured))> Produk unggulan</label>

    @if($errors->any())<div class="alert error">Mohon periksa kembali data produk.</div>@endif
    <button class="btn btn-gold">Simpan Produk</button>
</form>
<script>
    (function () {
        var priceInput = document.getElementById('priceUsdInput');
        var discountInput = document.getElementById('discountInput');
        var finalInput = document.getElementById('finalPriceInput');

        function calculateFinalPrice() {
            if (!priceInput || !discountInput || !finalInput) return;

            var price = parseFloat(priceInput.value);
            var discount = parseFloat(discountInput.value);

            if (Number.isNaN(price) || Number.isNaN(discount)) {
                finalInput.value = '';
                return;
            }

            discount = Math.min(100, Math.max(0, discount));
            finalInput.value = (price * (100 - discount) / 100).toFixed(2);
        }

        if (priceInput && discountInput) {
            priceInput.addEventListener('input', calculateFinalPrice);
            discountInput.addEventListener('input', calculateFinalPrice);
            calculateFinalPrice();
        }
    })();
</script>
@endsection

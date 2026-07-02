@extends('layouts.public')
@section('title','Pricelist EMKO Indonesia 2026 - Rupiah')
@section('content')
@php($hideCommercial = config('emko.hide_commercial_values'))
<section class="page-title">
    <p class="eyebrow">Indonesia 2026</p>
    <h1>Pricelist Produk EMKO</h1>
    <p>Harga ditampilkan dalam Rupiah dengan harga dasar dan harga diskon. Harga belum termasuk pajak, shipping, instalasi, konfigurasi, dan biaya proyek.</p>
</section>
<section class="section">
    <div class="table-wrap pricelist-table-wrap">
        <table class="pricelist-table">
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Kategori</th>
                    <th>Harga Dasar</th>
                    <th>Disc</th>
                    <th>Harga Diskon</th>
                    <th>Detail</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td><strong>{{ $product->product_code }}</strong><br><small>{{ $product->short_description }}</small></td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->formatted_price_idr }}</td>
                        <td>{{ $hideCommercial ? '' : number_format($product->discount_percent,0) . '%' }}</td>
                        <td><strong>{{ $product->formatted_final_price_idr }}</strong></td>
                        <td><a class="table-action detail" href="{{ route('products.show', $product) }}">Lihat Detail</a></td>
                        <td>
                            <div class="table-actions">
                                <a class="table-action buy" href="{{ route('checkout.create', $product) }}">Beli</a>
                                <a class="table-action quote" href="{{ route('quotation.create',['product'=>$product->id]) }}">Hubungi Sales</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

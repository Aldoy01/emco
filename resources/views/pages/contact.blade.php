@extends('layouts.public')
@section('title','Kontak Sales EMKO Indonesia')
@section('content')
@php
    $contactWaText = rawurlencode("Halo Sales EMKO, saya ingin konsultasi kebutuhan produk.\n\nNama:\nPerusahaan:\nProduk yang dibutuhkan:\nQty:\nLokasi proyek:");
    $contactWaLink = 'https://wa.me/' . config('emko.sales_whatsapp') . '?text=' . $contactWaText;
@endphp
<section class="page-title">
    <p class="eyebrow">Sales Channel</p>
    <h1>Kontak EMKO / Gencontrol Indonesia</h1>
    <p>Hubungi sales untuk konsultasi produk, quotation, shipping, availability, dan kebutuhan proyek.</p>
</section>
<section class="section split">
    <div>
        <h2>Channel Sales</h2>
        <p>Email: <a href="mailto:{{ config('emko.sales_email') }}">{{ config('emko.sales_email') }}</a></p>
        <p>WhatsApp: <a href="{{ $contactWaLink }}">{{ config('emko.sales_whatsapp') }}</a></p>
        <p>Area layanan: Indonesia</p>
    </div>
    <div class="quote-box">
        <span>Butuh AMF, ATS, atau Synchronizing?</span>
        <strong>Kami bantu pilihkan</strong>
        <a class="btn btn-gold" href="{{ route('quotation.create') }}">Hubungi Sales</a>
        <a class="btn btn-outline" href="{{ $contactWaLink }}">WhatsApp Sales</a>
    </div>
</section>
@endsection

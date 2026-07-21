@extends('layouts.public')
@section('title','Kontak Sales EMKO Indonesia')
@section('content')
@php
    $officeAddress = config('emko.office_address');
    $officeEmail = config('emko.office_email');
    $officePhone = config('emko.office_phone');
    $workEmail = config('emko.work_email');
    $workPhone = config('emko.work_phone');
    $contactWaText = rawurlencode("Halo Sales EMKO, saya ingin konsultasi kebutuhan produk.\n\nNama:\nPerusahaan:\nProduk yang dibutuhkan:\nQty:\nLokasi proyek:");
    $officeWaLink = 'https://wa.me/' . preg_replace('/\D+/', '', $officePhone) . '?text=' . $contactWaText;
    $workWaLink = 'https://wa.me/' . preg_replace('/\D+/', '', $workPhone) . '?text=' . $contactWaText;
@endphp
<section class="page-title contact-title">
    <p class="eyebrow">Contact Center</p>
    <h1>Hubungi EMKO Indonesia</h1>
    <p>Konsultasi produk, quotation, availability, shipping, dan kebutuhan konfigurasi proyek melalui channel resmi kami.</p>
</section>

<section class="section contact-section">
    <div class="contact-layout">
        <div class="contact-copy">
            <p class="eyebrow">Office & Work Office</p>
            <h2>Kontak resmi untuk kebutuhan produk dan penawaran</h2>
            <p>Alamat office dan work office menggunakan lokasi yang sama. Untuk komunikasi, gunakan email dan nomor kontak sesuai kebutuhan administrasi atau sales.</p>
            <div class="contact-actions">
                <a class="btn btn-gold" href="{{ route('quotation.create') }}">Minta Penawaran</a>
                <a class="btn btn-outline" href="{{ $workWaLink }}">WhatsApp Sales</a>
            </div>
        </div>

        <div class="contact-card-grid">
            <article class="contact-info-card primary">
                <div class="contact-icon" aria-hidden="true"><span></span></div>
                <div>
                    <p>Office</p>
                    <h3>Office Address</h3>
                    <address>{{ $officeAddress }}</address>
                </div>
                <ul>
                    <li><span>Email</span><a href="mailto:{{ $officeEmail }}">{{ $officeEmail }}</a></li>
                    <li><span>Nomor Kontak</span><a href="{{ $officeWaLink }}">{{ $officePhone }}</a></li>
                </ul>
            </article>

            <article class="contact-info-card">
                <div class="contact-icon work" aria-hidden="true"><span></span></div>
                <div>
                    <p>Work Office</p>
                    <h3>Work Office Address</h3>
                    <address>{{ $officeAddress }}</address>
                </div>
                <ul>
                    <li><span>Email</span><a href="mailto:{{ $workEmail }}">{{ $workEmail }}</a></li>
                    <li><span>Nomor Kontak</span><a href="{{ $workWaLink }}">{{ $workPhone }}</a></li>
                </ul>
            </article>
        </div>
    </div>
</section>
@endsection
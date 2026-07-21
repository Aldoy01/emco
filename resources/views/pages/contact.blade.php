@extends('layouts.public')
@section('title','Alamat Kami - EMKO Indonesia')
@section('content')
@php
    $officeAddress = config('emko.office_address');
    $officeEmail = config('emko.office_email');
    $officePhone = config('emko.office_phone');
    $workEmail = config('emko.work_email');
    $workPhone = config('emko.work_phone');
    $officeHours = config('emko.office_hours');
    $mapUrl = config('emko.map_url');
    $mapLink = config('emko.map_link');
    $contactWaText = rawurlencode("Halo Sales EMKO, saya ingin konsultasi kebutuhan produk.\n\nNama:\nPerusahaan:\nProduk yang dibutuhkan:\nQty:\nLokasi proyek:");
    $officeWaLink = 'https://wa.me/' . preg_replace('/\D+/', '', $officePhone) . '?text=' . $contactWaText;
    $workWaLink = 'https://wa.me/' . preg_replace('/\D+/', '', $workPhone) . '?text=' . $contactWaText;
@endphp
<section class="contact-address-page">
    <div class="contact-address-inner">
        <div class="contact-address-heading">
            <h1>Alamat kami</h1>
            <p>Konsultasi cepat untuk kebutuhan generator controller, ATS, synchronizing, dan dukungan instalasi. Tim kami bantu rekomendasi sesuai lokasi dan kebutuhan proyek.</p>
            <div class="contact-quick-actions">
                <a href="tel:{{ preg_replace('/\D+/', '', $officePhone) }}"><span>?</span>Telepon</a>
                <a href="{{ $workWaLink }}"><span>?</span>WhatsApp</a>
                <a href="mailto:{{ $workEmail }}"><span>?</span>Email</a>
            </div>
        </div>

        <div class="contact-address-grid">
            <form class="contact-message-card" method="post" action="{{ route('quotation.store') }}">
                @csrf
                <input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">
                <input type="hidden" name="company" value="Kontak Website">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="project_deadline" value="">
                @if($contactProduct)
                    <input type="hidden" name="product_id" value="{{ $contactProduct->id }}">
                @endif

                <h2>Kirimi kami pesan</h2>
                <p>Pesan</p>

                <label>Jenis Pesan
                    <select name="contact_type">
                        <option>Hal Umum</option>
                        <option>Konsultasi Produk</option>
                        <option>Permintaan Penawaran</option>
                        <option>Dukungan Instalasi</option>
                    </select>
                </label>
                <label>Nama Lengkap <b>*</b><input name="name" value="{{ old('name') }}" required></label>
                <label>Email <b>*</b><input type="email" name="email" value="{{ old('email') }}" required></label>
                <label>No Telepon<input name="phone" value="{{ old('phone') }}" required></label>
                <label>Judul Pesan<input name="project_location" value="{{ old('project_location') }}" placeholder="Contoh: Kebutuhan ATS untuk proyek" required></label>
                <label>Pesan <b>*</b><textarea name="technical_needs" rows="7" required>{{ old('technical_needs') }}</textarea></label>

                @if(!$contactProduct)
                    <div class="alert error">Produk default belum tersedia. Silakan hubungi sales melalui WhatsApp.</div>
                @endif
                @if($errors->any())
                    <div class="alert error">Mohon lengkapi data yang wajib diisi.</div>
                @endif
                @if(session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif
                <button class="contact-submit" type="submit" @disabled(!$contactProduct)>Kirim</button>
            </form>

            <aside class="contact-map-card">
                <h2>Mobile</h2>
                <div class="contact-lines">
                    <p><span>?</span><strong>WhatsApp CS:</strong> <a href="{{ $workWaLink }}">{{ $workPhone }}</a></p>
                    <p><span>?</span><strong>Email:</strong> <a href="mailto:{{ $workEmail }}">{{ $workEmail }}</a></p>
                    <p><span>?</span><strong>Jam kerja:</strong> {{ $officeHours }}</p>
                </div>

                <h3>Email</h3>
                <p><strong>Office:</strong> {{ $officeAddress }}</p>
                <p><strong>Work Office:</strong> {{ $officeAddress }}</p>
                <a class="maps-link" href="{{ $mapLink }}" target="_blank" rel="noopener">Lihat di Google Maps</a>

                <div class="map-frame">
                    <iframe src="{{ $mapUrl }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','EMKO Gencontrol Indonesia')</title>
    <link rel="stylesheet" href="{{ asset('css/emko.css') }}?v={{ filemtime(base_path('css/emko.css')) }}">
    @stack('styles')
</head>
<body class="public-shell">
    @php
        $salesWaText = rawurlencode("Halo Sales EMKO, saya ingin konsultasi produk EMKO / Gencontrol Indonesia.\n\nNama:\nPerusahaan:\nKebutuhan produk:\nQty:\nLokasi proyek:");
        $salesWaLink = 'https://wa.me/' . config('emko.sales_whatsapp') . '?text=' . $salesWaText;
    @endphp

    <header class="site-header modern-header">
        <a class="brand modern-brand brand-logo-link" href="{{ route('home') }}" aria-label="EMKO Partnership by Tramatekid">
            <img class="site-logo" src="{{ asset('images/emko-partnership.png') }}" alt="EMKO Partnership by Tramatekid">
        </a>
        <nav class="nav modern-nav">
            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="{{ request()->routeIs('products.*') || request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
            <a class="{{ request()->routeIs('pricelist') ? 'active' : '' }}" href="{{ route('pricelist') }}">Pricelist</a>
            <a class="{{ request()->routeIs('downloads') ? 'active' : '' }}" href="{{ route('downloads') }}">Download</a>
            <a class="{{ request()->routeIs('solutions') ? 'active' : '' }}" href="{{ route('solutions') }}">Solusi</a>
            <a class="{{ request()->routeIs('articles') ? 'active' : '' }}" href="{{ route('articles') }}">Artikel</a>
            <a class="{{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
        </nav>
        <div class="header-actions">
            @auth
                <div class="account-menu">
                    <button class="btn btn-soft account-toggle" type="button">Akun</button>
                    <div class="account-dropdown">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                        @else
                            <a href="{{ route('member.dashboard') }}">Dashboard Member</a>
                            <a href="{{ route('member.profile.edit') }}">Ganti Profil</a>
                            <a href="{{ route('member.password.edit') }}">Reset Password</a>
                        @endif
                        <form method="post" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
                    </div>
                </div>
            @else
                <a class="btn btn-soft" href="{{ route('login') }}">Login</a>
            @endauth
            <a class="btn btn-soft" href="{{ $salesWaLink }}">WhatsApp</a>
            <a class="btn btn-gold" href="{{ route('quotation.create') }}">Hubungi Sales</a>
        </div>
    </header>

    <main>@yield('content')</main>

    <div class="mobile-cta modern-mobile-cta">
        <a href="{{ $salesWaLink }}">WhatsApp</a>
        <a href="{{ route('quotation.create') }}">Hubungi Sales</a>
    </div>

    <footer class="footer modern-footer">
        <div class="footer-main">
            <div class="footer-brand-block">
                <a class="brand modern-brand footer-brand brand-logo-link" href="{{ route('home') }}" aria-label="EMKO Partnership by Tramatekid">
                    <img class="site-logo footer-logo" src="{{ asset('images/emko-partnership.png') }}" alt="EMKO Partnership by Tramatekid">
                </a>
                <p>Katalog B2B dan sistem pembelian/quotation untuk generator controller, ATS, synchronizing, monitoring, dan battery charger.</p>
                <div class="footer-badges"><span>Sales Ready</span><span>Invoice Flow</span><span>Rupiah Price</span></div>
            </div>
            <div class="footer-col">
                <strong>Produk</strong>
                <a href="{{ route('products.index') }}">Katalog Produk</a>
                <a href="{{ route('pricelist') }}">Pricelist Rupiah</a>
                <a href="{{ route('downloads') }}">Download Catalogue</a>
            </div>
            <div class="footer-col">
                <strong>Sales</strong>
                <a href="{{ route('quotation.create') }}">Hubungi Sales</a>
                <a href="{{ route('contact') }}">Kontak Sales</a>
                <a href="{{ $salesWaLink }}">WhatsApp</a>
                <a href="mailto:{{ config('emko.sales_email') }}">{{ config('emko.sales_email') }}</a>
            </div>
            <div class="footer-card">
                <strong>Catatan Harga</strong>
                <p>Harga estimasi Rupiah setelah diskon. Belum termasuk pajak, shipping, instalasi, konfigurasi, dan biaya proyek.</p>
                <a class="btn btn-gold" href="{{ route('quotation.create') }}">Konsultasi Produk</a>
            </div>
        </div>
        <div class="footer-bottom"><span>&copy; {{ date('Y') }} EMKO / Gencontrol Indonesia</span><span>B2B Product Catalogue + Hubungi Sales</span></div>
    </footer>
    @stack('scripts')
</body>
</html>
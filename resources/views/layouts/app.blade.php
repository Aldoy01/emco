<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EMKO Gencontrol Indonesia')</title>
    <meta name="description" content="@yield('description', 'EMKO Gencontrol Indonesia - Katalog produk dan sales channel generator controller.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #1a1a2e;
            --accent: #c9a96e;
            --accent-light: #e8d5b0;
            --light-bg: #faf9f7;
            --text-muted: #888;
            --border: #e8e0d8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            color: #2d2d2d;
            background: #fff;
        }

        h1, h2, h3, h4, .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* NAVBAR */
        .navbar-luxe {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .navbar-luxe .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary) !important;
            letter-spacing: 2px;
            padding: 18px 0;
        }

        .navbar-luxe .navbar-brand span {
            color: var(--accent);
        }

        .navbar-luxe .nav-link {
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--primary) !important;
            padding: 28px 18px !important;
            position: relative;
            transition: color 0.3s;
        }

        .navbar-luxe .nav-link::after {
            content: '';
            position: absolute;
            bottom: 20px;
            left: 18px;
            right: 18px;
            height: 1px;
            background: var(--accent);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .navbar-luxe .nav-link:hover::after,
        .navbar-luxe .nav-link.active::after { transform: scaleX(1); }

        .navbar-luxe .nav-link:hover,
        .navbar-luxe .nav-link.active { color: var(--accent) !important; }

        /* TOP BAR */
        .top-bar {
            background: var(--primary);
            color: rgba(255,255,255,0.85);
            font-size: 0.72rem;
            letter-spacing: 1px;
            text-align: center;
            padding: 8px 0;
        }

        /* BUTTONS */
        .btn-luxe {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 14px 36px;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
            transition: all 0.3s;
            border-radius: 0;
        }

        .btn-luxe:hover {
            background: var(--accent);
            color: var(--primary);
        }

        .btn-luxe-outline {
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
            padding: 12px 34px;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
            transition: all 0.3s;
            border-radius: 0;
        }

        .btn-luxe-outline:hover {
            background: var(--primary);
            color: #fff;
        }

        .btn-accent {
            background: var(--accent);
            color: var(--primary);
            border: none;
            padding: 14px 36px;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
            transition: all 0.3s;
            border-radius: 0;
        }

        .btn-accent:hover {
            background: var(--primary);
            color: #fff;
        }

        /* SECTION STYLES */
        .section-label {
            font-size: 0.7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 12px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.2;
        }

        /* PRODUCT CARD */
        .product-card {
            border: none;
            border-radius: 0;
            overflow: hidden;
            transition: transform 0.3s;
            background: #fff;
        }

        .product-card:hover { transform: translateY(-6px); }

        .product-card .img-wrapper {
            position: relative;
            overflow: hidden;
            aspect-ratio: 3/4;
            background: var(--light-bg);
        }

        .product-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover img { transform: scale(1.05); }

        .product-card .badge-sale {
            position: absolute;
            top: 16px;
            left: 16px;
            background: var(--accent);
            color: var(--primary);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 5px 10px;
        }

        .product-card .badge-new {
            position: absolute;
            top: 16px;
            left: 16px;
            background: var(--primary);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 5px 10px;
        }

        .product-card .card-body { padding: 16px 0; }

        .product-card .category-label {
            font-size: 0.65rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .product-card .product-name {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            color: var(--primary);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .product-card .price {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary);
        }

        .product-card .price-original {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-decoration: line-through;
            margin-right: 8px;
        }

        /* FOOTER */
        footer {
            background: var(--primary);
            color: rgba(255,255,255,0.75);
        }

        footer .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 2px;
        }

        footer .footer-brand span { color: var(--accent); }

        footer h6 {
            font-size: 0.7rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 20px;
        }

        footer a {
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.3s;
            display: block;
            margin-bottom: 10px;
        }

        footer a:hover { color: var(--accent); }

        footer .footer-divider {
            border-color: rgba(255,255,255,0.1);
            margin: 40px 0 24px;
        }

        footer .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s;
            margin-right: 8px;
        }

        footer .social-link:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: var(--primary);
        }

        /* ALERTS */
        .alert-luxe-success {
            background: #f0f7f4;
            border: 1px solid #b7dfcf;
            color: #1e5c3e;
            border-radius: 0;
            font-size: 0.9rem;
        }

        /* BREADCRUMB */
        .breadcrumb-luxe {
            background: var(--light-bg);
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }

        .breadcrumb-luxe .breadcrumb {
            margin: 0;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
        }

        .breadcrumb-luxe .breadcrumb-item a {
            color: var(--text-muted);
            text-decoration: none;
        }

        .breadcrumb-luxe .breadcrumb-item.active { color: var(--primary); }

        /* SCROLLBAR */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--light-bg); }
        ::-webkit-scrollbar-thumb { background: var(--accent-light); border-radius: 3px; }

        @media (max-width: 768px) {
            .section-title { font-size: 1.8rem; }
            .navbar-luxe .nav-link { padding: 12px 8px !important; }
            .navbar-luxe .nav-link::after { display: none; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="container">
        <span><i class="bi bi-truck me-1"></i> Gratis Ongkir untuk pembelian di atas Rp 500.000</span>
        <span class="mx-3">|</span>
        <span><i class="bi bi-stars me-1"></i> Premium Quality Since 2018</span>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-luxe navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            EM<span>K</span>O
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('collections') ? 'active' : '' }}" href="{{ route('collections') }}">Collections</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}" href="{{ route('products') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('products') }}?search=" class="text-dark" style="font-size:1.1rem;"><i class="bi bi-search"></i></a>
                @auth
                <a href="{{ route('admin.dashboard') }}" class="text-dark" style="font-size:1.1rem;" title="Admin"><i class="bi bi-grid"></i></a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main>
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="pt-5 pb-4">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="footer-brand mb-3">EM<span>K</span>O</div>
                <p style="font-size:0.88rem; line-height:1.8; color:rgba(255,255,255,0.6);">
                    Katalog produk dan sales channel EMKO / Gencontrol Indonesia untuk generator controller, ATS, synchronizing, monitoring, dan battery charger.
                </p>
                <div class="mt-4">
                    <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6>Navigation</h6>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('collections') }}">Collections</a>
                <a href="{{ route('products') }}">Products</a>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>
            <div class="col-lg-2 col-6">
                <h6>Categories</h6>
                <a href="{{ route('products') }}?category=wanita">Wanita</a>
                <a href="{{ route('products') }}?category=pria">Pria</a>
                <a href="{{ route('products') }}?category=aksesori">Aksesori</a>
                <a href="{{ route('products') }}?category=sepatu">Sepatu</a>
                <a href="{{ route('products') }}?category=tas">Tas</a>
            </div>
            <div class="col-lg-4">
                <h6>Newsletter</h6>
                <p style="font-size:0.85rem; color:rgba(255,255,255,0.6); margin-bottom:16px;">
                    Daftarkan email Anda dan dapatkan penawaran eksklusif pertama.
                </p>
                <form class="d-flex gap-0">
                    <input type="email" class="form-control rounded-0 border-0" placeholder="Email Anda..." style="background:rgba(255,255,255,0.1); color:#fff; font-size:0.85rem;" >
                    <button class="btn-accent" type="submit" style="padding:10px 20px; font-size:0.7rem; letter-spacing:1px; white-space:nowrap;">Subscribe</button>
                </form>
                <div class="mt-4" style="font-size:0.82rem;">
                    <p class="mb-1"><i class="bi bi-geo-alt me-2" style="color:var(--accent)"></i>Indonesia</p>
                    <p class="mb-1"><i class="bi bi-telephone me-2" style="color:var(--accent)"></i>{{ config('emko.sales_whatsapp') }}</p>
                    <p><i class="bi bi-envelope me-2" style="color:var(--accent)"></i>{{ config('emko.sales_email') }}</p>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center" style="font-size:0.78rem; color:rgba(255,255,255,0.45);">
            <p class="mb-0">&copy; {{ date('Y') }} EMKO Gencontrol Indonesia. All rights reserved.</p>
            <div class="mt-2 mt-md-0">
                <a href="#" style="display:inline; margin-right:16px; font-size:0.78rem; color:rgba(255,255,255,0.45);">Privacy Policy</a>
                <a href="#" style="display:inline; font-size:0.78rem; color:rgba(255,255,255,0.45);">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>

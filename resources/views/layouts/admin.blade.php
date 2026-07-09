<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin EMKO CRM')</title>
    <link rel="stylesheet" href="{{ asset('css/emko.css') }}?v={{ filemtime(base_path('css/emko.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-orders.css') }}?v={{ filemtime(base_path('css/admin-orders.css')) }}">
</head>
<body class="admin-body crm-shell">
    <aside class="admin-sidebar crm-sidebar">
        <a class="brand crm-brand crm-logo-link" href="{{ route('admin.dashboard') }}" aria-label="EMKO Partnership by Tramatekid">
            <img class="crm-logo" src="{{ asset('images/emko-partnership.png') }}" alt="EMKO Partnership by Tramatekid">
            <small>Sales CRM</small>
        </a>
        <nav class="crm-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span>DB</span>Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><span>PR</span>Produk & Harga</a>
            <a href="{{ route('admin.quotations.index') }}" class="{{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}"><span>RF</span>Sales Leads</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><span>PY</span>Pembelian & Pembayaran</a>
            <a href="{{ route('admin.content.home.edit') }}" class="{{ request()->routeIs('admin.content.*') ? 'active' : '' }}"><span>HM</span>Konten Home</a>
            <a href="{{ route('admin.finance.edit') }}" class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}"><span>FN</span>Finance & Invoice</a>
            <a href="{{ route('admin.security.index') }}" class="{{ request()->routeIs('admin.security.*') ? 'active' : '' }}"><span>SC</span>Security Logs</a>
            <a href="{{ route('home') }}"><span>WB</span>Website</a>
        </nav>
        <div class="crm-sidebar-card">
            <strong>B2B Pipeline</strong>
            <p>Katalog, quotation, follow-up, dan closing dalam satu dashboard.</p>
        </div>
        <form method="post" action="{{ route('logout') }}" class="crm-logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </aside>
    <main class="admin-main crm-main">
        <header class="crm-topbar">
            <div>
                <p class="crm-kicker">EMKO Gencontrol Indonesia</p>
                <h1>@yield('page_title', 'Sales Dashboard')</h1>
            </div>
            <div class="crm-user-chip">
                <span>{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                <div><strong>{{ auth()->user()->name ?? 'Admin' }}</strong><small>Administrator</small></div>
            </div>
        </header>
        @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @yield('content')
    </main>
</body>
</html>

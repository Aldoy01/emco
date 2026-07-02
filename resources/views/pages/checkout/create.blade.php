@extends('layouts.public')
@section('title', 'Tinjau dan Checkout ' . $product->product_name)
@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}?v={{ filemtime(base_path('css/checkout.css')) }}">
@endpush
@section('content')
@php
    $hideCommercial = config('emko.hide_commercial_values');
    $qty = old('quantity', $quantity);
    $lineTotal = $product->final_price_idr * (int) $qty;
    $originalTotal = $product->price_idr * (int) $qty;
    $saving = max(0, $originalTotal - $lineTotal);
    $regions = [
        'DKI Jakarta' => ['Jakarta Pusat','Jakarta Utara','Jakarta Barat','Jakarta Selatan','Jakarta Timur','Kepulauan Seribu'],
        'Banten' => ['Tangerang','Tangerang Selatan','Serang','Cilegon','Pandeglang','Lebak'],
        'Jawa Barat' => ['Bandung','Bekasi','Bogor','Depok','Cimahi','Cirebon','Karawang','Sukabumi','Tasikmalaya'],
        'Jawa Tengah' => ['Semarang','Surakarta','Magelang','Pekalongan','Tegal','Purwokerto','Kudus','Cilacap'],
        'DI Yogyakarta' => ['Yogyakarta','Sleman','Bantul','Kulon Progo','Gunungkidul'],
        'Jawa Timur' => ['Surabaya','Sidoarjo','Gresik','Malang','Kediri','Madiun','Pasuruan','Mojokerto','Jember'],
        'Bali' => ['Denpasar','Badung','Gianyar','Tabanan','Buleleng','Karangasem'],
        'Sumatera Utara' => ['Medan','Binjai','Deli Serdang','Pematangsiantar','Tebing Tinggi'],
        'Sumatera Barat' => ['Padang','Bukittinggi','Payakumbuh','Pariaman','Solok'],
        'Sumatera Selatan' => ['Palembang','Prabumulih','Lubuklinggau','Pagar Alam'],
        'Riau' => ['Pekanbaru','Dumai','Siak','Bengkalis'],
        'Kepulauan Riau' => ['Batam','Tanjung Pinang','Bintan','Karimun'],
        'Lampung' => ['Bandar Lampung','Metro','Lampung Selatan','Lampung Tengah'],
        'Kalimantan Barat' => ['Pontianak','Singkawang','Ketapang','Sambas'],
        'Kalimantan Timur' => ['Samarinda','Balikpapan','Bontang','Kutai Kartanegara'],
        'Kalimantan Selatan' => ['Banjarmasin','Banjarbaru','Martapura','Tanah Laut'],
        'Sulawesi Selatan' => ['Makassar','Gowa','Maros','Parepare','Palopo'],
        'Sulawesi Utara' => ['Manado','Bitung','Tomohon','Minahasa'],
        'Nusa Tenggara Barat' => ['Mataram','Lombok Barat','Lombok Tengah','Sumbawa'],
        'Nusa Tenggara Timur' => ['Kupang','Ende','Maumere','Labuan Bajo'],
        'Papua' => ['Jayapura','Merauke','Timika','Nabire'],
    ];
    $selectedProvince = old('province');
    $selectedCity = old('city');
@endphp
<section class="checkout-page"><div class="checkout-review-shell">
    <div class="checkout-titlebar">
        <h1>Tinjau dan Checkout</h1>
        <div class="currency-toggle"><span class="active">IDR</span><span>USD</span></div>
    </div>

    <div class="review-table">
        <div class="review-head"><strong>Deskripsi</strong><strong>Harga</strong></div>
        <div class="review-row product-line"><div><strong>{{ $product->product_name }}</strong><span>{{ $product->short_description }}</span></div><div><strong>{{ $product->formatted_final_price_idr }}</strong><span>{{ $hideCommercial ? '' : 'Per unit' }}</span></div></div>
        <div class="review-row"><div>Quantity pembelian</div><div><strong>{{ $hideCommercial ? '' : $qty . ' unit' }}</strong></div></div>
        <div class="review-row subtotal-line"><div>Subtotal</div><div>{{ $hideCommercial ? '' : 'Rp ' . number_format($lineTotal, 0, ',', '.') }}</div></div>
        <div class="review-row discount-line"><div>{{ $hideCommercial ? '' : 'Hemat promo ' . number_format($product->discount_percent, 0) . '%' }}</div><div><del>{{ $hideCommercial ? '' : 'Rp ' . number_format($saving, 0, ',', '.') }}</del></div></div>
        <div class="review-total"><span>Total yang dibayar sekarang:</span><strong>{{ $hideCommercial ? '' : 'Rp ' . number_format($lineTotal, 0, ',', '.') }}</strong></div>
        <div class="review-note"><span>Tagihan berikutnya:</span><strong>Menunggu validasi pajak, shipping, instalasi, dan konfigurasi proyek.</strong></div>
    </div>

    <section class="checkout-account-card">
        <div class="checkout-card-head"><h2>Rincian Anda</h2>@auth<span class="member-status">Login sebagai {{ auth()->user()->name }}</span>@endauth</div>

        @guest
            <div class="member-tabs" role="tablist"><button class="active" type="button" data-tab="new-member">Member Baru</button><button type="button" data-tab="existing-member">Sudah Member</button></div>
            <form class="checkout-login-panel" id="existing-member" method="post" action="{{ route('checkout.login', $product) }}">
                @csrf
                <h3>Masuk sebagai member</h3><p>Jika sudah punya akun, masuk dulu lalu lanjutkan checkout produk ini.</p>
                <div class="form-grid"><label>Email Akun<input type="email" name="login_email" value="{{ old('login_email') }}" required></label><label>Password<input type="password" name="login_password" required></label></div>
                @error('login_email')<div class="alert error">{{ $message }}</div>@enderror
                <button class="btn btn-gold" type="submit">Masuk & Lanjut Checkout</button>
            </form>
        @endguest

        <form class="checkout-detail-form" id="new-member" method="post" action="{{ route('checkout.store', $product) }}">
            @csrf
            @guest<div class="signup-note"><strong>Sign Up</strong><span>Isi data pembeli dan buat password. Invoice akan diterbitkan setelah pemesanan selesai.</span></div>@else<div class="alert success">Akun pembeli sudah aktif. Lengkapi data pengiriman untuk menerbitkan invoice.</div>@endguest
            <div class="checkout-form-grid">
                <div class="checkout-column">
                    <label>Nama Lengkap<input name="customer_name" value="{{ old('customer_name', optional($user)->name) }}" required><small>Pastikan nama sesuai identitas atau data perusahaan.</small></label>
                    <label>Nama Perusahaan <span>(Opsional)</span><input name="company" value="{{ old('company') }}" placeholder="-"></label>
                    <label>Alamat Email<input type="email" name="email" value="{{ old('email', optional($user)->email) }}" @auth readonly @endauth required><small>Email ini dipakai untuk akun, invoice, dan status pembayaran.</small></label>
                    <label>Negara<select name="country" id="countrySelect" required><option value="Indonesia" selected>Indonesia</option></select></label>
                    <label>Nomor Telepon / WhatsApp<input name="phone" value="{{ old('phone') }}" placeholder="0812-345-678" required></label>
                    <label>Quantity<input type="number" min="1" max="999" name="quantity" value="{{ $hideCommercial ? old('quantity') : $qty }}" required></label>
                </div>
                <div class="checkout-column">
                    <label>Alamat Utama<textarea name="shipping_address" rows="4" required>{{ old('shipping_address') }}</textarea></label>
                    <label>Provinsi<select name="province" id="provinceSelect" required><option value="">Pilih Provinsi</option>@foreach($regions as $province => $cities)<option value="{{ $province }}" @selected($selectedProvince === $province)>{{ $province }}</option>@endforeach</select></label>
                    <label>Kota<select name="city" id="citySelect" data-selected="{{ $selectedCity }}" required><option value="">Pilih Kota</option></select></label>
                    <label>Kode Pos<input name="postal_code" value="{{ old('postal_code') }}"></label>
                    @guest
                        <label>Sandi<input type="password" name="password" required></label>
                        <div class="password-tip"><strong>Tips password yang baik</strong><span>Gunakan minimal 10 karakter dengan huruf besar, kecil, angka, dan simbol.</span></div>
                        <label>Konfirmasi Sandi<input type="password" name="password_confirmation" required></label>
                    @endguest
                    <label>Catatan Pembelian <span>(Opsional)</span><textarea name="notes" rows="3" placeholder="Nama proyek, NPWP, deadline, atau catatan invoice">{{ old('notes') }}</textarea></label>
                </div>
            </div>
            @if($errors->any() && ! $errors->has('login_email'))<div class="alert error">Mohon periksa kembali data checkout.</div>@endif
            <div class="checkout-paybar"><span>Total yang dibayar sekarang:</span><strong>{{ $hideCommercial ? '' : 'Rp ' . number_format($lineTotal, 0, ',', '.') }}</strong><button class="btn btn-gold" type="submit">Selesaikan Pemesanan</button></div>
        </form>
    </section>
</div></section>
<script>
    document.querySelectorAll('.member-tabs button').forEach(function(button){
        button.addEventListener('click', function(){
            document.querySelectorAll('.member-tabs button').forEach(function(item){ item.classList.remove('active'); });
            button.classList.add('active');
            var target = button.getAttribute('data-tab');
            document.getElementById('new-member').style.display = target === 'new-member' ? 'grid' : 'none';
            document.getElementById('existing-member').style.display = target === 'existing-member' ? 'grid' : 'none';
        });
    });
    var indonesiaRegions = @json($regions);
    var provinceSelect = document.getElementById('provinceSelect');
    var citySelect = document.getElementById('citySelect');
    function fillCities() {
        if (!provinceSelect || !citySelect) return;
        var selectedCity = citySelect.getAttribute('data-selected') || '';
        var cities = indonesiaRegions[provinceSelect.value] || [];
        citySelect.innerHTML = '<option value="">Pilih Kota</option>';
        cities.forEach(function(city){
            var option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            option.selected = city === selectedCity;
            citySelect.appendChild(option);
        });
        citySelect.removeAttribute('data-selected');
    }
    if (provinceSelect) {
        provinceSelect.addEventListener('change', fillCities);
        fillCities();
    }
</script>
@endsection

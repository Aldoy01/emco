@extends('layouts.public')
@section('title','Daftar Akun Pembeli EMKO')
@section('content')
<section class="page-title">
    <p class="eyebrow">Akun Pembeli</p>
    <h1>Daftar sebelum checkout</h1>
    <p>Buat akun pembeli agar invoice, pembayaran, dan konfirmasi order tercatat dengan aman.</p>
</section>
<section class="section narrow auth-section">
    <form class="rfq-form checkout-form auth-card" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="auth-card-head">
            <h2>Buat Akun Pembeli</h2>
            <p>Setelah daftar, Anda akan kembali ke halaman checkout produk.</p>
        </div>
        <div class="form-grid">
            <label>Nama Lengkap
                <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>Password
                <input type="password" name="password" required autocomplete="new-password">
                @error('password')<small class="field-error">{{ $message }}</small>@enderror
            </label>
            <label>Konfirmasi Password
                <input type="password" name="password_confirmation" required autocomplete="new-password">
            </label>
        </div>
        <button class="btn btn-gold" type="submit">Daftar & Lanjut Checkout</button>
        <p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </form>
</section>
@endsection
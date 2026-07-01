@extends('layouts.public')

@section('title','Reset Password - EMCO Indonesia')

@section('content')
<section class="page-title auth-title">
    <p class="eyebrow">Akun Pembeli</p>
    <h1>Reset Password</h1>
    <p>Buat password baru untuk masuk kembali ke akun EMCO Anda.</p>
</section>

<section class="section auth-section">
    <form class="auth-card rfq-form" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label>Email Akun
            <input type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
            @error('email')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <label>Password Baru
            <input type="password" name="password" required autocomplete="new-password">
            @error('password')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <label>Konfirmasi Password Baru
            <input type="password" name="password_confirmation" required autocomplete="new-password">
        </label>

        <div class="form-actions">
            <button class="btn btn-gold" type="submit">Update Password</button>
            <a class="btn btn-outline" href="{{ route('login') }}">Kembali Login</a>
        </div>
    </form>
</section>
@endsection

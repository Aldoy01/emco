@extends('layouts.public')

@section('title','Lupa Password - EMCO Indonesia')

@section('content')
<section class="page-title auth-title">
    <p class="eyebrow">Akun Pembeli</p>
    <h1>Lupa Password</h1>
    <p>Masukkan email akun Anda. Kami akan mengirimkan link untuk membuat password baru.</p>
</section>

<section class="section auth-section">
    <form class="auth-card rfq-form" method="POST" action="{{ route('password.email') }}">
        @csrf

        @if (session('status'))
            <div class="alert success">{{ session('status') }}</div>
        @endif

        <label>Email Akun
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <div class="form-actions">
            <button class="btn btn-gold" type="submit">Kirim Link Reset</button>
            <a class="btn btn-outline" href="{{ route('login') }}">Kembali Login</a>
        </div>
    </form>
</section>
@endsection

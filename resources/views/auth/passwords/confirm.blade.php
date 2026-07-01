@extends('layouts.public')

@section('title','Konfirmasi Password - EMCO Indonesia')

@section('content')
<section class="page-title auth-title">
    <p class="eyebrow">Keamanan Akun</p>
    <h1>Konfirmasi Password</h1>
    <p>Masukkan password akun Anda untuk melanjutkan proses ini.</p>
</section>

<section class="section auth-section">
    <form class="auth-card rfq-form" method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <label>Password
            <input type="password" name="password" required autocomplete="current-password">
            @error('password')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <div class="form-actions">
            <button class="btn btn-gold" type="submit">Konfirmasi</button>
            @if (Route::has('password.request'))
                <a class="btn btn-outline" href="{{ route('password.request') }}">Lupa Password</a>
            @endif
        </div>
    </form>
</section>
@endsection

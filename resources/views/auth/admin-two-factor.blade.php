@extends('layouts.public')

@section('title','Verifikasi Admin - EMCO Indonesia')

@section('content')
<section class="page-title auth-title">
    <p class="eyebrow">Keamanan Admin</p>
    <h1>Verifikasi Login Admin</h1>
    <p>Masukkan kode 6 digit yang dikirim ke email admin.</p>
</section>

<section class="section auth-section">
    <form class="auth-card rfq-form" method="POST" action="{{ route('admin.two-factor.verify') }}">
        @csrf

        @if (session('status'))
            <div class="alert success">{{ session('status') }}</div>
        @endif

        @if ($debugCode)
            <div class="alert success">Mode local/debug: kode admin {{ $debugCode }}</div>
        @endif

        <label>Kode Verifikasi
            <input type="text" name="code" inputmode="numeric" maxlength="6" required autofocus>
            @error('code')<small class="field-error">{{ $message }}</small>@enderror
        </label>

        <div class="form-actions">
            <button class="btn btn-gold" type="submit">Verifikasi</button>
            <button class="btn btn-outline" type="submit" form="resend-admin-code">Kirim Ulang</button>
        </div>
    </form>

    <form id="resend-admin-code" method="POST" action="{{ route('admin.two-factor.resend') }}">
        @csrf
    </form>
</section>
@endsection

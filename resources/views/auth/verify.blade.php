@extends('layouts.public')

@section('title','Verifikasi Email - EMCO Indonesia')

@section('content')
<section class="page-title auth-title">
    <p class="eyebrow">Verifikasi Akun</p>
    <h1>Verifikasi Email</h1>
    <p>Cek email Anda untuk mengaktifkan akun pembeli EMCO.</p>
</section>

<section class="section auth-section">
    <div class="auth-card rfq-form">
        @if (session('resent'))
            <div class="alert success">Link verifikasi baru sudah dikirim ke email Anda.</div>
        @endif

        <p>Sebelum melanjutkan, buka email Anda dan klik link verifikasi akun. Jika belum menerima email, kirim ulang link verifikasi.</p>

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <div class="form-actions">
                <button class="btn btn-gold" type="submit">Kirim Ulang Verifikasi</button>
                <a class="btn btn-outline" href="{{ route('home') }}">Kembali ke Home</a>
            </div>
        </form>
    </div>
</section>
@endsection

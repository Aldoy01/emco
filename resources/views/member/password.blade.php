@extends('layouts.public')
@section('title','Reset Password Member')
@section('content')
<section class="page-title member-title"><p class="eyebrow">Member Area</p><h1>Reset Password</h1><p>Ganti password akun member Anda secara aman.</p></section>
<section class="section member-dashboard">
    <form class="member-panel rfq-form" method="post" action="{{ route('member.password.update') }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <label>Password Lama<input type="password" name="current_password" required></label>
            <label>Password Baru<input type="password" name="password" required></label>
            <label>Konfirmasi Password Baru<input type="password" name="password_confirmation" required></label>
        </div>
        @if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
        <div class="form-actions"><button class="btn btn-gold">Update Password</button><a class="btn btn-outline" href="{{ route('member.dashboard') }}">Kembali ke Dashboard</a></div>
    </form>
</section>
@endsection
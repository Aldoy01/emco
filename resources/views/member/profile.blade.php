@extends('layouts.public')
@section('title','Ganti Profil Member')
@section('content')
<section class="page-title member-title"><p class="eyebrow">Member Area</p><h1>Ganti Profil</h1><p>Perbarui nama dan email akun pembeli.</p></section>
<section class="section member-dashboard">
    <form class="member-panel rfq-form" method="post" action="{{ route('member.profile.update') }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <label>Nama<input name="name" value="{{ old('name', $user->name) }}" required></label>
            <label>Email<input type="email" name="email" value="{{ old('email', $user->email) }}" required></label>
        </div>
        @if($errors->any())<div class="alert error">Mohon periksa kembali data profil.</div>@endif
        <div class="form-actions"><button class="btn btn-gold">Simpan Profil</button><a class="btn btn-outline" href="{{ route('member.dashboard') }}">Kembali ke Dashboard</a></div>
    </form>
</section>
@endsection
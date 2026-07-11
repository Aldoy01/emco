@extends('layouts.admin')
@section('title', ($user->exists ? 'Edit User' : 'Tambah User Admin') . ' - CRM EMKO')
@section('page_title', $user->exists ? 'Edit User' : 'Tambah User Admin')
@section('content')
<section class="crm-hero-panel small">
    <div>
        <p class="crm-kicker">{{ $user->exists ? 'User profile' : 'New admin account' }}</p>
        <h2>{{ $user->exists ? 'Perbarui akses, role, dan password user.' : 'Buat akun admin untuk finance atau pengiriman.' }}</h2>
    </div>
    <a class="btn btn-outline" href="{{ route('admin.users.index') }}">Kembali</a>
</section>

<section class="admin-order-detail">
    <form class="admin-panel crm-card rfq-form" method="post" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if($user->exists) @method('PUT') @endif
        <div class="section-head compact"><div><p class="crm-kicker">Account</p><h2>Data User</h2></div></div>
        <div class="form-grid">
            <label>Nama
                <input name="name" value="{{ old('name', $user->name) }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </label>
        </div>

        @if($user->exists)
            <label class="checkbox-line"><input type="checkbox" name="is_admin" value="1" @checked(old('is_admin', $user->is_admin))> Jadikan akun admin</label>
        @else
            <input type="hidden" name="is_admin" value="1">
        @endif

        <label>Role Admin
            <select name="admin_role">
                @foreach($roles as $key => $label)
                    <option value="{{ $key }}" @selected(old('admin_role', $user->admin_role ?: App\Models\User::ROLE_FINANCE) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </label>

        @unless($user->exists)
            <div class="form-grid">
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label>Konfirmasi Password
                    <input type="password" name="password_confirmation" required>
                </label>
            </div>
        @endunless

        @if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
        <button class="btn btn-gold" type="submit">{{ $user->exists ? 'Simpan User' : 'Buat User Admin' }}</button>
    </form>

    @if($user->exists)
        <form class="admin-panel crm-card rfq-form" method="post" action="{{ route('admin.users.reset-password', $user) }}">
            @csrf
            @method('PUT')
            <div class="section-head compact"><div><p class="crm-kicker">Password reset</p><h2>Reset Password</h2></div></div>
            <p class="muted-copy">Gunakan saat user lupa password atau perlu diganti oleh Super Admin.</p>
            <label>Password Baru
                <input type="password" name="password" required>
            </label>
            <label>Konfirmasi Password Baru
                <input type="password" name="password_confirmation" required>
            </label>
            <button class="btn btn-outline" type="submit">Reset Password</button>
        </form>
    @endif
</section>
@endsection
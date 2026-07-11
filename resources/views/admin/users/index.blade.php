@extends('layouts.admin')
@section('title', 'Manajemen User - CRM EMKO')
@section('page_title', 'Manajemen User')
@section('content')
<section class="crm-hero-panel small">
    <div>
        <p class="crm-kicker">Access control</p>
        <h2>Kelola akun Super Admin, Finance, Pengiriman, dan member pembeli.</h2>
    </div>
    <a class="btn btn-gold" href="{{ route('admin.users.create') }}">Tambah User Admin</a>
</section>

<section class="admin-panel crm-card">
    <form class="admin-order-filter" method="get" action="{{ route('admin.users.index') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama atau email">
        <select name="role">
            <option value="">Semua user</option>
            @foreach($roles as $key => $label)
                <option value="{{ $key }}" @selected(request('role') === $key)>{{ $label }}</option>
            @endforeach
            <option value="member" @selected(request('role') === 'member')>Member Pembeli</option>
        </select>
        <button class="btn btn-gold" type="submit">Filter</button>
    </form>

    <div class="table-wrap crm-table-wrap">
        <table class="crm-table">
            <thead><tr><th>User</th><th>Role</th><th>Status</th><th>Dibuat</th><th></th></tr></thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong><br><small>{{ $user->email }}</small></td>
                    <td><span class="status-pill status-{{ $user->admin_role ?: 'member' }}">{{ $user->roleLabel() }}</span></td>
                    <td>{{ $user->is_admin ? 'Admin' : 'Member' }}<br><small>{{ $user->email_verified_at ? 'Email verified' : 'Belum verified' }}</small></td>
                    <td>{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</td>
                    <td><a class="soft-link" href="{{ route('admin.users.edit', $user) }}">Kelola</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty-cell">Belum ada user sesuai filter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
<div class="crm-pagination">{{ $users->links() }}</div>
@endsection
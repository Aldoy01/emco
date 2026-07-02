@extends('layouts.admin')

@section('title','Security Logs')

@section('content')
<div class="crm-hero-panel small">
    <div>
        <p class="crm-kicker">Security</p>
        <h2>Audit Login</h2>
        <p>Aktivitas login berhasil, gagal, dan verifikasi admin 2FA.</p>
    </div>
</div>

<form class="admin-order-filter" method="get">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari email, IP, atau konteks">
    <select name="status">
        <option value="">Semua status</option>
        @foreach($statuses as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ str_replace('_', ' ', $status) }}</option>
        @endforeach
    </select>
    <button class="btn btn-gold">Filter</button>
</form>

<div class="admin-panel crm-card">
    <div class="table-wrap crm-table-wrap">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>IP</th>
                    <th>Konteks</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $audit)
                    <tr>
                        <td>{{ $audit->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $audit->email ?: '-' }}</td>
                        <td><span class="status-pill status-{{ $audit->status }}">{{ str_replace('_', ' ', $audit->status) }}</span></td>
                        <td>{{ $audit->ip_address ?: '-' }}</td>
                        <td>{{ $audit->context ?: '-' }}</td>
                        <td><small>{{ \Illuminate\Support\Str::limit($audit->user_agent ?: '-', 90) }}</small></td>
                    </tr>
                @empty
                    <tr><td class="empty-cell" colspan="6">Belum ada audit login.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="crm-pagination">{{ $audits->links() }}</div>
</div>
@endsection

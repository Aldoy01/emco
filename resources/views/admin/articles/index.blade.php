@extends('layouts.admin')
@section('title','Kelola Artikel')
@section('page_title','Kelola Artikel')
@section('content')
<div class="admin-panel crm-card">
    <div class="admin-table-header">
        <div>
            <p class="crm-kicker">Blog & Insight</p>
            <h2>Artikel Website</h2>
        </div>
        <a class="btn btn-gold" href="{{ route('admin.articles.create') }}">Tambah Artikel</a>
    </div>

    <div class="responsive-table">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Publikasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>
                            <strong>{{ $article->title }}</strong>
                            <small>{{ $article->excerpt }}</small>
                        </td>
                        <td>{{ $article->category ?: '-' }}</td>
                        <td><span class="status-pill {{ $article->status === 'published' ? 'paid' : 'pending' }}">{{ $article->status === 'published' ? 'Published' : 'Draft' }}</span></td>
                        <td>{{ optional($article->published_at)->format('d M Y H:i') ?: '-' }}</td>
                        <td class="table-actions">
                            <a class="btn btn-soft" href="{{ route('admin.articles.edit', $article) }}">Edit</a>
                            <form method="post" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $articles->links() }}
</div>
@endsection

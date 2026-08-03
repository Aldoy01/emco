@extends('layouts.public')
@section('title','Artikel EMKO Indonesia')
@section('content')
<section class="page-title article-hero">
    <p class="eyebrow">Posting Blog</p>
    <h1>Artikel Terbaru Kami.</h1>
    <p>Insight produk, sistem genset, ATS, AMF, synchronizing, dan tips memilih controller yang sesuai kebutuhan proyek.</p>
</section>

<section class="section article-blog-section">
    <div class="article-section-head">
        <div>
            <span class="section-kicker">EMKO Insight</span>
            <h2>Panduan teknis dan referensi produk</h2>
        </div>
        <span>{{ $articles->total() }} artikel</span>
    </div>

    <div class="article-card-grid">
        @forelse($articles as $article)
            <article class="blog-card">
                <a class="blog-card-image {{ $article->image ? 'has-image' : '' }}" href="{{ route('articles.show', $article) }}" style="position:relative;display:block;width:100%;aspect-ratio:16/9;overflow:hidden;background:linear-gradient(135deg,#eef8f6,#f8fafc);">
                    @if($article->image)
                        <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" style="position:absolute;inset:0;width:100%;height:100%;display:block;object-fit:cover;object-position:center;" onerror="this.style.display='none';this.nextElementSibling.style.display='grid';">
                        <span class="product-placeholder-icon" aria-hidden="true" style="display:none;place-items:center;width:100%;height:100%;"></span>
                    @else
                        <span class="product-placeholder-icon" aria-hidden="true"></span>
                    @endif
                </a>
                <div class="blog-card-body">
                    <span class="blog-category">{{ $article->category ?: 'EMKO Insight' }}</span>
                    <h2><a href="{{ route('articles.show', $article) }}">{{ $article->title }}</a></h2>
                    <p>{{ $article->excerpt ?: Str::limit(strip_tags($article->body), 145) }}</p>
                    <div class="blog-meta">
                        <span>{{ optional($article->published_at)->format('d.M.Y') }}</span>
                        <span>{{ $article->author_name }}</span>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty-catalog-state">
                <strong>Artikel sedang disiapkan.</strong>
                <p>Konten edukasi EMKO akan tampil di halaman ini setelah dipublikasikan dari admin.</p>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $articles->links() }}
    </div>
</section>
@endsection

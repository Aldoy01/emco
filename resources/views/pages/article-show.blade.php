@extends('layouts.public')
@section('title', $article->title)
@section('content')
<section class="page-title article-detail-hero">
    <p class="eyebrow">{{ $article->category ?: 'EMKO Insight' }}</p>
    <h1>{{ $article->title }}</h1>
    <p>{{ $article->excerpt }}</p>
    <div class="article-detail-meta">
        <span>{{ optional($article->published_at)->format('d M Y') }}</span>
        <span>{{ $article->author_name }}</span>
    </div>
</section>

<section class="section article-detail-section">
    @if($article->image)
        <div class="article-cover">
            <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
        </div>
    @endif

    <article class="article-content">
        {!! $article->body !!}
    </article>

    @if($relatedArticles->isNotEmpty())
        <div class="related-articles">
            <div class="article-section-head">
                <div>
                    <span class="section-kicker">Artikel Lainnya</span>
                    <h2>Referensi terkait</h2>
                </div>
            </div>
            <div class="article-card-grid compact">
                @foreach($relatedArticles as $relatedArticle)
                    <article class="blog-card">
                        <a class="blog-card-image {{ $relatedArticle->image ? 'has-image' : '' }}" href="{{ route('articles.show', $relatedArticle) }}">
                            @if($relatedArticle->image)
                                <img src="{{ asset($relatedArticle->image) }}" alt="{{ $relatedArticle->title }}">
                            @else
                                <span class="product-placeholder-icon" aria-hidden="true"></span>
                            @endif
                        </a>
                        <div class="blog-card-body">
                            <span class="blog-category">{{ $relatedArticle->category ?: 'EMKO Insight' }}</span>
                            <h2><a href="{{ route('articles.show', $relatedArticle) }}">{{ $relatedArticle->title }}</a></h2>
                            <div class="blog-meta">
                                <span>{{ optional($relatedArticle->published_at)->format('d.M.Y') }}</span>
                                <span>{{ $relatedArticle->author_name }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection

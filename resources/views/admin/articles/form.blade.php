@extends('layouts.admin')
@section('title', $article->exists ? 'Edit Artikel' : 'Tambah Artikel')
@section('page_title', $article->exists ? 'Edit Artikel' : 'Tambah Artikel')
@section('content')
<form class="rfq-form admin-panel crm-card article-form" method="post" enctype="multipart/form-data" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
    @csrf
    @if($article->exists) @method('PUT') @endif

    <div class="form-grid">
        <label>Judul Artikel<input name="title" value="{{ old('title', $article->title) }}" required></label>
        <label>Kategori<input name="category" value="{{ old('category', $article->category) }}" placeholder="Contoh: Product Insight"></label>
        <label>Penulis<input name="author_name" value="{{ old('author_name', $article->author_name ?: auth()->user()->name) }}"></label>
        <label>Status
            <select name="status">
                <option value="draft" @selected(old('status', $article->status ?: 'draft') === 'draft')>Draft</option>
                <option value="published" @selected(old('status', $article->status) === 'published')>Published</option>
            </select>
        </label>
        <label>Tanggal Publish<input type="datetime-local" name="published_at" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}"></label>
        <label>Gambar Artikel<input type="file" name="image" accept="image/jpeg,image/png,image/webp"><small>Format JPG, PNG, WEBP. Maksimal 2 MB.</small></label>
    </div>

    @if($article->image)
        <div class="article-admin-preview">
            <img src="{{ asset($article->image) }}" alt="{{ $article->title }}">
        </div>
    @endif

    <label>Ringkasan Artikel<textarea name="excerpt" maxlength="500" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea></label>

    <label>Isi Artikel</label>
    <div class="word-editor-shell">
        <div class="word-editor-toolbar" role="toolbar" aria-label="Toolbar artikel">
            <button type="button" data-command="bold"><strong>B</strong></button>
            <button type="button" data-command="italic"><em>I</em></button>
            <button type="button" data-command="underline"><u>U</u></button>
            <button type="button" data-format="h2">H2</button>
            <button type="button" data-format="h3">H3</button>
            <button type="button" data-command="insertUnorderedList">Bullet</button>
            <button type="button" data-command="insertOrderedList">Nomor</button>
            <button type="button" data-command="formatBlock" data-value="blockquote">Quote</button>
            <button type="button" data-link="true">Link</button>
            <button type="button" data-command="removeFormat">Clear</button>
        </div>
        <div id="articleEditor" class="word-editor" contenteditable="true">{!! old('body', $article->body) !!}</div>
    </div>
    <textarea id="articleBodyInput" name="body" hidden>{{ old('body', $article->body) }}</textarea>

    @if($errors->any())
        <div class="alert error">
            <strong>Artikel belum bisa disimpan.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button class="btn btn-gold" type="submit">Simpan Artikel</button>
</form>

<script>
    (function () {
        var form = document.querySelector('.article-form');
        var editor = document.getElementById('articleEditor');
        var input = document.getElementById('articleBodyInput');
        var toolbar = document.querySelector('.word-editor-toolbar');

        function syncBody() {
            input.value = editor.innerHTML.trim();
        }

        toolbar.addEventListener('click', function (event) {
            var button = event.target.closest('button');
            if (!button) return;

            editor.focus();
            if (button.dataset.format) {
                document.execCommand('formatBlock', false, button.dataset.format);
            } else if (button.dataset.link) {
                var url = window.prompt('Masukkan URL link');
                if (url) document.execCommand('createLink', false, url);
            } else {
                document.execCommand(button.dataset.command, false, button.dataset.value || null);
            }
            syncBody();
        });

        editor.addEventListener('input', syncBody);
        form.addEventListener('submit', syncBody);
    })();
</script>
@endsection

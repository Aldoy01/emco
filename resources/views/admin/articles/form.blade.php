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
        <div class="word-editor-ribbon" role="toolbar" aria-label="Toolbar artikel">
            <div class="word-editor-group">
                <span>Dokumen</span>
                <button type="button" title="Undo" data-command="undo">Undo</button>
                <button type="button" title="Redo" data-command="redo">Redo</button>
            </div>
            <div class="word-editor-group">
                <span>Format</span>
                <select id="formatBlockSelect" title="Style paragraf">
                    <option value="p">Paragraph</option>
                    <option value="h1">Heading 1</option>
                    <option value="h2">Heading 2</option>
                    <option value="h3">Heading 3</option>
                    <option value="blockquote">Quote</option>
                </select>
                <select id="fontSizeSelect" title="Ukuran font">
                    <option value="">Size</option>
                    <option value="2">Small</option>
                    <option value="3">Normal</option>
                    <option value="4">Medium</option>
                    <option value="5">Large</option>
                    <option value="6">XL</option>
                </select>
            </div>
            <div class="word-editor-group compact">
                <span>Teks</span>
                <button type="button" title="Bold" data-command="bold"><strong>B</strong></button>
                <button type="button" title="Italic" data-command="italic"><em>I</em></button>
                <button type="button" title="Underline" data-command="underline"><u>U</u></button>
                <button type="button" title="Strikethrough" data-command="strikeThrough"><s>S</s></button>
                <label class="color-tool" title="Warna teks">A<input type="color" id="textColorInput" value="#10243f"></label>
                <label class="color-tool highlight" title="Highlight">H<input type="color" id="highlightColorInput" value="#fff2bf"></label>
            </div>
            <div class="word-editor-group compact">
                <span>Paragraf</span>
                <button type="button" title="Rata kiri" data-command="justifyLeft">Left</button>
                <button type="button" title="Rata tengah" data-command="justifyCenter">Center</button>
                <button type="button" title="Rata kanan" data-command="justifyRight">Right</button>
                <button type="button" title="Rata penuh" data-command="justifyFull">Justify</button>
                <button type="button" title="Bullet list" data-command="insertUnorderedList">Bullet</button>
                <button type="button" title="Number list" data-command="insertOrderedList">Nomor</button>
                <button type="button" title="Kurangi indent" data-command="outdent">Outdent</button>
                <button type="button" title="Tambah indent" data-command="indent">Indent</button>
            </div>
            <div class="word-editor-group compact">
                <span>Insert</span>
                <button type="button" data-link="true">Link</button>
                <button type="button" data-command="insertHorizontalRule">Garis</button>
                <button type="button" data-command="removeFormat">Clear</button>
            </div>
        </div>
        <div class="word-editor-canvas">
            <div id="articleEditor" class="word-editor" contenteditable="true" data-placeholder="Tulis artikel di sini...">{!! old('body', $article->body) !!}</div>
        </div>
        <div class="word-editor-status">
            <span id="wordCount">0 kata</span>
            <span>Auto save ke form saat diketik</span>
        </div>
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
        var ribbon = document.querySelector('.word-editor-ribbon');
        var formatSelect = document.getElementById('formatBlockSelect');
        var fontSizeSelect = document.getElementById('fontSizeSelect');
        var textColorInput = document.getElementById('textColorInput');
        var highlightColorInput = document.getElementById('highlightColorInput');
        var wordCount = document.getElementById('wordCount');

        function syncBody() {
            input.value = editor.innerHTML.trim();
            var text = editor.innerText.trim();
            wordCount.textContent = (text ? text.split(/\s+/).length : 0) + ' kata';
        }

        function exec(command, value) {
            editor.focus();
            document.execCommand(command, false, value || null);
            syncBody();
        }

        ribbon.addEventListener('click', function (event) {
            var button = event.target.closest('button');
            if (!button) return;

            if (button.dataset.link) {
                var url = window.prompt('Masukkan URL link');
                if (url) exec('createLink', url);
            } else {
                exec(button.dataset.command, button.dataset.value);
            }
        });

        formatSelect.addEventListener('change', function () {
            exec('formatBlock', this.value);
            this.value = 'p';
        });

        fontSizeSelect.addEventListener('change', function () {
            if (this.value) exec('fontSize', this.value);
            this.value = '';
        });

        textColorInput.addEventListener('input', function () {
            exec('foreColor', this.value);
        });

        highlightColorInput.addEventListener('input', function () {
            exec('hiliteColor', this.value);
        });

        editor.addEventListener('input', syncBody);
        form.addEventListener('submit', syncBody);
        syncBody();
    })();
</script>
@endsection

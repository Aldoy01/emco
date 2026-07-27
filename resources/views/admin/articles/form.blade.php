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
    <div class="cms-editor-shell minimal-cms-editor">
        <textarea id="articleBodyInput" class="rich-cms-editor" name="body">{{ old('body', $article->body) }}</textarea>
    </div>

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

<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script>
    (function () {
        var form = document.querySelector('.article-form');

        if (!window.tinymce) {
            return;
        }

        tinymce.init({
            selector: '#articleBodyInput',
            license_key: 'gpl',
            base_url: "{{ asset('vendor/tinymce') }}",
            suffix: '.min',
            height: 360,
            branding: false,
            promotion: false,
            menubar: false,
            statusbar: false,
            plugins: 'advlist autolink lists link image preview searchreplace visualblocks code fullscreen media table wordcount autoresize',
            toolbar_mode: 'scrolling',
            toolbar: 'blocks h2Button h3Button h4Button | bold italic strikethrough link forecolor | bullist numlist | alignleft aligncenter alignright alignjustify | image media blockquote code visualblocks | hr table | undo redo',
            block_formats: 'Heading=h1; Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Quote=blockquote',
            font_size_formats: '14px 16px 18px 20px 24px 28px 32px',
            paste_data_images: true,
            image_title: true,
            automatic_uploads: false,
            file_picker_types: 'image',
            content_style: 'body{font-family:Inter,Arial,sans-serif;color:#10243f;font-size:16px;line-height:1.75;padding:24px;background:#edf3ff;}h1,h2,h3,h4{line-height:1.2;color:#10243f;}blockquote{margin:18px 0;padding:14px 18px;border-left:4px solid #167a7f;border-radius:10px;background:#ffffff;color:#10243f;}table{border-collapse:collapse;width:100%;background:#fff;}td,th{border:1px solid #dbe5ef;padding:10px;}img{max-width:100%;height:auto;}',
            setup: function (editor) {
                editor.ui.registry.addButton('h2Button', {
                    text: 'H2',
                    tooltip: 'Heading 2',
                    onAction: function () {
                        editor.execCommand('FormatBlock', false, 'h2');
                    }
                });
                editor.ui.registry.addButton('h3Button', {
                    text: 'H3',
                    tooltip: 'Heading 3',
                    onAction: function () {
                        editor.execCommand('FormatBlock', false, 'h3');
                    }
                });
                editor.ui.registry.addButton('h4Button', {
                    text: 'H4',
                    tooltip: 'Heading 4',
                    onAction: function () {
                        editor.execCommand('FormatBlock', false, 'h4');
                    }
                });
                editor.on('change keyup undo redo', function () {
                    editor.save();
                });
            }
        });

        form.addEventListener('submit', function () {
            tinymce.triggerSave();
        });
    })();
</script>
@endsection

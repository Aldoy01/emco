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
    <div class="cms-editor-shell">
        <div class="cms-editor-head">
            <div>
                <strong>CMS Article Editor</strong>
                <span>Editor profesional dengan preview, fullscreen, code view, table, image, media, word count, dan paste dari Word/Google Docs.</span>
            </div>
            <small>Self-hosted TinyMCE</small>
        </div>
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
            height: 660,
            branding: false,
            promotion: false,
            menubar: 'file edit view insert format tools table help',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount autoresize',
            toolbar_mode: 'sliding',
            toolbar: [
                'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor removeformat',
                'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table hr | preview fullscreen code'
            ].join(' | '),
            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Quote=blockquote',
            font_family_formats: 'Inter=Inter,Arial,sans-serif; Arial=arial,helvetica,sans-serif; Georgia=georgia,palatino,serif; Times New Roman=times new roman,times,serif; Courier New=courier new,courier,monospace',
            font_size_formats: '12px 14px 16px 18px 20px 24px 28px 32px 40px',
            paste_data_images: true,
            image_title: true,
            automatic_uploads: false,
            file_picker_types: 'image',
            content_style: 'body{font-family:Inter,Arial,sans-serif;color:#10243f;font-size:16px;line-height:1.75;padding:24px;}h1,h2,h3{line-height:1.2;color:#10243f;}blockquote{margin:18px 0;padding:14px 18px;border-left:4px solid #167a7f;border-radius:10px;background:#f4fbfa;color:#10243f;}table{border-collapse:collapse;width:100%;}td,th{border:1px solid #dbe5ef;padding:10px;}img{max-width:100%;height:auto;}',
            setup: function (editor) {
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

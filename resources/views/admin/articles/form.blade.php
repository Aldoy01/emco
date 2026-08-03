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
    </div>

    <section class="article-thumbnail-uploader">
        <div class="article-thumbnail-copy">
            <span>Thumbnail Artikel</span>
            <strong>Gambar cover untuk kartu blog dan halaman detail</strong>
            <small>Gunakan gambar horizontal agar tampil proporsional. Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>
        </div>
        <label class="article-thumbnail-drop" for="articleImageInput">
            <div class="article-thumbnail-preview {{ $article->image ? 'has-image' : '' }}">
                @if($article->image)
                    <img id="articleImagePreview" src="{{ asset($article->image) }}" alt="{{ $article->title }}">
                    <span id="articleImagePlaceholder" class="product-placeholder-icon" aria-hidden="true"></span>
                @else
                    <img id="articleImagePreview" src="" alt="" hidden>
                    <span id="articleImagePlaceholder" class="product-placeholder-icon" aria-hidden="true"></span>
                @endif
            </div>
            <div class="article-thumbnail-action">
                <span>Pilih gambar thumbnail</span>
                <small>Klik area ini untuk upload dari komputer.</small>
            </div>
            <input id="articleImageInput" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </label>
    </section>

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
        var thumbnailInput = document.getElementById('articleImageInput');
        var thumbnailPreview = document.getElementById('articleImagePreview');
        var thumbnailPlaceholder = document.getElementById('articleImagePlaceholder');

        if (thumbnailInput && thumbnailPreview) {
            thumbnailInput.addEventListener('change', function () {
                var file = thumbnailInput.files && thumbnailInput.files[0];

                if (!file) {
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (event) {
                    thumbnailPreview.src = event.target.result;
                    thumbnailPreview.hidden = false;
                    thumbnailPreview.parentElement.classList.add('has-image');

                    if (thumbnailPlaceholder) {
                        thumbnailPlaceholder.hidden = true;
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        if (!window.tinymce) {
            return;
        }

        function uploadArticleImage(file, filename) {
            return new Promise(function (resolve, reject) {
                var formData = new FormData();
                formData.append('file', file, filename || file.name || 'artikel-image');

                fetch("{{ route('admin.articles.upload-image') }}", {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(function (response) {
                    if (!response.ok) {
                        reject('Upload gambar gagal. Pastikan format JPG, PNG, atau WEBP maksimal 2 MB.');
                        return null;
                    }
                    return response.json();
                }).then(function (json) {
                    if (!json || !json.location) {
                        reject('Upload gambar gagal. URL gambar tidak diterima server.');
                        return;
                    }
                    resolve(json.location);
                }).catch(function () {
                    reject('Upload gambar gagal. Coba ulangi beberapa saat lagi.');
                });
            });
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
            toolbar: 'blocks h2Button h3Button h4Button | bold italic strikethrough link forecolor | bullist numlist | alignleft aligncenter alignright alignjustify | uploadArticleImage image media blockquote code visualblocks | hr table | undo redo',
            block_formats: 'Heading=h1; Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Quote=blockquote',
            font_size_formats: '14px 16px 18px 20px 24px 28px 32px',
            paste_data_images: true,
            image_title: true,
            automatic_uploads: true,
            images_upload_handler: function (blobInfo, progress) {
                return uploadArticleImage(blobInfo.blob(), blobInfo.filename());
            },
            file_picker_types: 'image',
            file_picker_callback: function (callback, value, meta) {
                if (meta.filetype !== 'image') {
                    return;
                }

                var input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/jpeg,image/png,image/webp';

                input.addEventListener('change', function () {
                    var file = input.files && input.files[0];

                    if (!file) {
                        return;
                    }

                    uploadArticleImage(file, file.name).then(function (location) {
                        callback(location, {
                            title: file.name,
                            alt: file.name
                        });
                    }).catch(function (message) {
                        tinymce.activeEditor.notificationManager.open({
                            text: message,
                            type: 'error',
                            timeout: 5000
                        });
                    });
                });

                input.click();
            },
            content_style: 'body{font-family:Inter,Arial,sans-serif;color:#10243f;font-size:16px;line-height:1.75;padding:24px;background:#edf3ff;}h1,h2,h3,h4{line-height:1.2;color:#10243f;}blockquote{margin:18px 0;padding:14px 18px;border-left:4px solid #167a7f;border-radius:10px;background:#ffffff;color:#10243f;}table{border-collapse:collapse;width:100%;background:#fff;}td,th{border:1px solid #dbe5ef;padding:10px;}img{max-width:100%;height:auto;}',
            setup: function (editor) {
                function openArticleImageBrowser() {
                    var input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/jpeg,image/png,image/webp';

                    input.addEventListener('change', function () {
                        var file = input.files && input.files[0];

                        if (!file) {
                            return;
                        }

                        uploadArticleImage(file, file.name).then(function (location) {
                            editor.insertContent('<figure class="article-inline-image"><img src="' + location + '" alt="' + file.name.replace(/"/g, '&quot;') + '"></figure>');
                            editor.save();
                        }).catch(function (message) {
                            editor.notificationManager.open({
                                text: message,
                                type: 'error',
                                timeout: 5000
                            });
                        });
                    });

                    input.click();
                }

                editor.ui.registry.addButton('uploadArticleImage', {
                    text: 'Upload Gambar',
                    tooltip: 'Upload gambar dari komputer',
                    onAction: openArticleImageBrowser
                });
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

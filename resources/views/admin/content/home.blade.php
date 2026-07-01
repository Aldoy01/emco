@extends('layouts.admin')
@section('title','Manajemen Konten Home')
@section('page_title','Konten Home')
@section('content')
<form class="rfq-form admin-panel crm-card content-form" method="post" enctype="multipart/form-data" action="{{ route('admin.content.home.update') }}">
    @csrf
    @method('PUT')

    <section class="content-section-block">
        <div class="section-head compact"><div><p class="crm-kicker">Hero</p><h2>Konten Utama</h2></div></div>
        <div class="form-grid">
            <label>Eyebrow<input name="hero_eyebrow" value="{{ old('hero_eyebrow',$content['hero_eyebrow'] ?? '') }}" required></label>
            <label>Judul Hero<input name="hero_title" value="{{ old('hero_title',$content['hero_title'] ?? '') }}" required></label>
            <label>Label Tombol Utama<input name="hero_primary_label" value="{{ old('hero_primary_label',$content['hero_primary_label'] ?? '') }}" required></label>
            <label>Label Tombol Kedua<input name="hero_secondary_label" value="{{ old('hero_secondary_label',$content['hero_secondary_label'] ?? '') }}" required></label>
        </div>
        <label>Subtitle Hero<textarea name="hero_subtitle" rows="4" required>{{ old('hero_subtitle',$content['hero_subtitle'] ?? '') }}</textarea></label>
        <div class="hero-upload-grid">
            <div class="image-preview-box home-hero-preview">
                @if(!empty($content['hero_image']))
                    <img src="{{ asset($content['hero_image']) }}" alt="Header Home">
                @else
                    <span>HOME</span>
                @endif
            </div>
            <label>Upload Gambar Header Home
                <input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp">
                <small>Format JPG, PNG, atau WEBP. Maksimal 4 MB. Jika kosong, gambar lama tetap dipakai.</small>
            </label>
        </div>
    </section>

    <section class="content-section-block">
        <div class="section-head compact"><div><p class="crm-kicker">Quick Access</p><h2>Panel Cepat</h2></div></div>
        <label>Judul Panel<input name="quick_title" value="{{ old('quick_title',$content['quick_title'] ?? '') }}" required></label>
        <label>Item Panel, satu baris per item<textarea name="quick_items" rows="4" required>{{ old('quick_items', implode("\n", $content['quick_items'] ?? [])) }}</textarea></label>
    </section>

    <section class="content-section-block">
        <div class="section-head compact"><div><p class="crm-kicker">Benefits</p><h2>Keunggulan</h2></div></div>
        <label>Judul Section<input name="benefit_heading" value="{{ old('benefit_heading',$content['benefit_heading'] ?? '') }}" required></label>
        <label>Benefit, format: Judul | Deskripsi<textarea name="benefits" rows="7" required>{{ old('benefits', collect($content['benefits'] ?? [])->map(fn($item) => ($item['title'] ?? '') . ' | ' . ($item['body'] ?? ''))->implode("\n")) }}</textarea></label>
    </section>

    <section class="content-section-block">
        <div class="section-head compact"><div><p class="crm-kicker">Flow</p><h2>Alur Pembelian</h2></div></div>
        <label>Judul Section<input name="flow_heading" value="{{ old('flow_heading',$content['flow_heading'] ?? '') }}" required></label>
        <label>Alur, format: Judul | Deskripsi<textarea name="flows" rows="7" required>{{ old('flows', collect($content['flows'] ?? [])->map(fn($item) => ($item['title'] ?? '') . ' | ' . ($item['body'] ?? ''))->implode("\n")) }}</textarea></label>
    </section>

    <section class="content-section-block">
        <div class="section-head compact"><div><p class="crm-kicker">CTA</p><h2>Call To Action</h2></div></div>
        <div class="form-grid">
            <label>Eyebrow CTA<input name="cta_eyebrow" value="{{ old('cta_eyebrow',$content['cta_eyebrow'] ?? '') }}" required></label>
            <label>Judul CTA<input name="cta_title" value="{{ old('cta_title',$content['cta_title'] ?? '') }}" required></label>
        </div>
        <label>Teks CTA<textarea name="cta_text" rows="4" required>{{ old('cta_text',$content['cta_text'] ?? '') }}</textarea></label>
    </section>

    @if($errors->any())<div class="alert error">Mohon periksa kembali konten Home.</div>@endif
    <div class="form-actions"><button class="btn btn-gold">Simpan Konten Home</button><a class="btn btn-outline" href="{{ route('home') }}">Preview Website</a></div>
</form>
@endsection
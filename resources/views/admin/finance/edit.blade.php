@extends('layouts.admin')
@section('title','Informasi Finance & Invoice')
@section('page_title','Finance & Invoice')
@section('content')
<form class="rfq-form admin-panel crm-card content-form" method="post" action="{{ route('admin.finance.update') }}">
    @csrf
    @method('PUT')

    <section class="content-section-block">
        <div class="section-head compact"><div><p class="crm-kicker">Invoice</p><h2>Keterangan Pembayaran</h2></div></div>
        <div class="form-grid">
            <label>Nama Perusahaan Invoice<input name="invoice_company" value="{{ old('invoice_company', $finance['invoice_company'] ?? '') }}" required></label>
            <label>Nama Finance<input name="finance_name" value="{{ old('finance_name', $finance['finance_name'] ?? '') }}"></label>
            <label>Email Finance<input type="email" name="finance_email" value="{{ old('finance_email', $finance['finance_email'] ?? '') }}"></label>
            <label>Telepon / WhatsApp Finance<input name="finance_phone" value="{{ old('finance_phone', $finance['finance_phone'] ?? '') }}"></label>
        </div>
        <label>Pengantar Instruksi Pembayaran<textarea name="payment_intro" rows="3" required>{{ old('payment_intro', $finance['payment_intro'] ?? '') }}</textarea></label>
        <label>Informasi Finance<textarea name="finance_information" rows="4" placeholder="Contoh: Pembayaran akan diverifikasi pada hari kerja pukul 09.00-17.00.">{{ old('finance_information', $finance['finance_information'] ?? '') }}</textarea></label>
    </section>

    <section class="content-section-block">
        <div class="section-head compact"><div><p class="crm-kicker">Bank Account</p><h2>Rekening Tujuan</h2></div></div>
        <label>Daftar Rekening, format: Bank | Nomor Rekening | Nama Pemilik<textarea name="bank_accounts" rows="7" required>{{ old('bank_accounts', collect($finance['bank_accounts'] ?? [])->map(fn($item) => ($item['bank'] ?? '') . ' | ' . ($item['account_number'] ?? '') . ' | ' . ($item['account_name'] ?? ''))->implode("\n")) }}</textarea><small>Contoh: BCA | 1234567890 | PT Gencontrol Indonesia</small></label>
        <label>Catatan Berita Transfer<textarea name="transfer_note" rows="3" required>{{ old('transfer_note', $finance['transfer_note'] ?? '') }}</textarea></label>
    </section>

    @if($errors->any())
        <div class="alert error">
            <strong>Informasi finance belum bisa disimpan.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-actions"><button class="btn btn-gold">Simpan Finance</button><a class="btn btn-outline" href="{{ route('admin.orders.index') }}">Lihat Invoice</a></div>
</form>
@endsection

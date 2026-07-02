<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><title>Lead Baru EMKO</title></head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,sans-serif;color:#172033;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:24px;">
<tr><td align="center">
<table width="640" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #d8dee8;border-radius:12px;overflow:hidden;">
<tr><td style="padding:24px;background:#10243f;color:#ffffff;"><h1 style="margin:0;font-size:22px;">Lead Baru Hubungi Sales</h1><p style="margin:8px 0 0;color:#c9d4e3;">EMKO / Gencontrol Indonesia</p></td></tr>
<tr><td style="padding:24px;">
<p style="margin-top:0;">Ada customer mengirim kebutuhan produk melalui website.</p>
<table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Nama</td><td style="border-bottom:1px solid #e5eaf2;"><strong>{{ $quotation->lead->name }}</strong></td></tr>
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Perusahaan</td><td style="border-bottom:1px solid #e5eaf2;">{{ $quotation->lead->company }}</td></tr>
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Email</td><td style="border-bottom:1px solid #e5eaf2;">{{ $quotation->lead->email }}</td></tr>
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Telepon/WA</td><td style="border-bottom:1px solid #e5eaf2;">{{ $quotation->lead->phone }}</td></tr>
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Produk</td><td style="border-bottom:1px solid #e5eaf2;"><strong>{{ $quotation->product->product_name }}</strong></td></tr>
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Qty</td><td style="border-bottom:1px solid #e5eaf2;">{{ config('emko.hide_commercial_values') ? '' : $quotation->quantity }}</td></tr>
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Lokasi Proyek</td><td style="border-bottom:1px solid #e5eaf2;">{{ $quotation->project_location }}</td></tr>
<tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Deadline</td><td style="border-bottom:1px solid #e5eaf2;">{{ optional($quotation->project_deadline)->format('d M Y') ?: '-' }}</td></tr>
</table>
<h2 style="font-size:16px;margin:22px 0 8px;">Kebutuhan Teknis</h2>
<div style="padding:14px;background:#f8fafc;border:1px solid #e5eaf2;border-radius:8px;line-height:1.6;">{{ $quotation->technical_needs ?: '-' }}</div>
<p style="margin-bottom:0;color:#667085;font-size:13px;">Status awal: New. Silakan follow-up customer melalui WhatsApp atau email.</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>

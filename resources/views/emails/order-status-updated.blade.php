@php
    $invoiceUrl = route('checkout.invoice', $order);
@endphp
<div style="margin:0;padding:28px;background:#f4f7fb;font-family:Arial,sans-serif;color:#172033;">
    <table width="640" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #d8dee8;border-radius:12px;overflow:hidden;">
        <tr>
            <td style="padding:24px;background:#10243f;color:#ffffff;">
                <strong style="font-size:22px;">Update Status Pembelian EMKO</strong>
                <p style="margin:8px 0 0;color:#d8e3f3;">{{ $order->invoice_number }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding:24px;">
                <p style="margin-top:0;">Halo <strong>{{ $order->customer_name }}</strong>,</p>
                <p>Status pembelian Anda telah diperbarui. Berikut informasi terbaru dari sistem EMKO.</p>

                <div style="padding:16px;background:#f8fafc;border:1px solid #e5eaf2;border-radius:10px;margin:18px 0;">
                    <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
                        <tr><td style="color:#667085;border-bottom:1px solid #e5eaf2;">Invoice</td><td style="border-bottom:1px solid #e5eaf2;"><strong>{{ $order->invoice_number }}</strong></td></tr>
                        <tr><td style="color:#667085;border-bottom:1px solid #e5eaf2;">Produk</td><td style="border-bottom:1px solid #e5eaf2;">{{ $order->product->product_name }}</td></tr>
                        <tr><td style="color:#667085;border-bottom:1px solid #e5eaf2;">Status sebelumnya</td><td style="border-bottom:1px solid #e5eaf2;">{{ $previousStatusLabel }}</td></tr>
                        <tr><td style="color:#667085;border-bottom:1px solid #e5eaf2;">Status terbaru</td><td style="border-bottom:1px solid #e5eaf2;"><strong style="color:#10243f;">{{ $newStatusLabel }}</strong></td></tr>
                        <tr><td style="color:#667085;">Total invoice</td><td><strong>{{ $order->formatted_total }}</strong></td></tr>
                    </table>
                </div>

                @if($adminNote)
                    <div style="padding:14px;background:#fff8e8;border:1px solid #f2d28a;border-radius:10px;line-height:1.6;margin-bottom:18px;">
                        <strong>Catatan Admin</strong><br>
                        {{ $adminNote }}
                    </div>
                @endif

                <p style="margin:22px 0;">
                    <a href="{{ $invoiceUrl }}" style="display:inline-block;padding:12px 16px;background:#c99a2e;color:#172033;text-decoration:none;border-radius:8px;font-weight:bold;">Lihat Invoice</a>
                </p>

                <p style="margin-bottom:0;color:#667085;font-size:13px;">Email ini dikirim otomatis oleh sistem EMKO Indonesia ketika status pembelian diperbarui.</p>
            </td>
        </tr>
    </table>
</div>
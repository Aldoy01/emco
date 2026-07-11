@php
    $invoiceUrl = route('checkout.invoice', $order);
    $confirmUrl = route('checkout.confirm', $order);
@endphp
<div style="margin:0;padding:28px;background:#f4f7fb;font-family:Arial,sans-serif;color:#172033;">
    <table width="640" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #d8dee8;border-radius:12px;overflow:hidden;">
        <tr>
            <td style="padding:24px;background:#10243f;color:#ffffff;">
                <strong style="font-size:22px;">Invoice Pembelian EMKO</strong>
                <p style="margin:8px 0 0;color:#d8e3f3;">{{ $order->invoice_number }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding:24px;">
                <p style="margin-top:0;">Halo <strong>{{ $order->customer_name }}</strong>,</p>
                <p>Terima kasih. Invoice pembelian Anda sudah dibuat. Berikut ringkasan transaksi dan informasi pembayaran.</p>

                <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;margin:18px 0;">
                    <tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Produk</td><td style="border-bottom:1px solid #e5eaf2;"><strong>{{ $order->product->product_name }}</strong></td></tr>
                    <tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Qty</td><td style="border-bottom:1px solid #e5eaf2;">{{ $order->quantity }}</td></tr>
                    <tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Harga Unit</td><td style="border-bottom:1px solid #e5eaf2;">{{ $order->formatted_unit_price }}</td></tr>
                    <tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Subtotal</td><td style="border-bottom:1px solid #e5eaf2;">{{ $order->formatted_subtotal }}</td></tr>
                    <tr><td style="border-bottom:1px solid #e5eaf2;color:#667085;">Total Invoice</td><td style="border-bottom:1px solid #e5eaf2;"><strong>{{ $order->formatted_total }}</strong></td></tr>
                </table>

                @if($order->product->purchase_information)
                    <div style="padding:14px;background:#f8fafc;border:1px solid #e5eaf2;border-radius:8px;line-height:1.6;margin-bottom:18px;">
                        <strong>Informasi Pembelian</strong><br>
                        {{ $order->product->purchase_information }}
                    </div>
                @endif

                <h3 style="margin-bottom:8px;">Instruksi Pembayaran</h3>
                <p>{{ $finance['payment_intro'] ?? 'Silakan transfer ke rekening berikut.' }}</p>
                @foreach($finance['bank_accounts'] ?? [] as $account)
                    <div style="padding:12px;border:1px solid #e5eaf2;border-radius:8px;margin-bottom:8px;">
                        <strong>{{ $account['bank'] ?? '-' }}</strong><br>
                        <span style="font-size:18px;color:#10243f;">{{ $account['account_number'] ?? '-' }}</span><br>
                        <small>{{ $account['account_name'] ?? '-' }}</small>
                    </div>
                @endforeach

                <p>Berita transfer: <strong>{{ $order->invoice_number }}</strong></p>
                @if(!empty($finance['transfer_note']))<p>{{ $finance['transfer_note'] }}</p>@endif
                @if(!empty($finance['finance_information']))<p>{{ $finance['finance_information'] }}</p>@endif

                <p style="margin:22px 0;">
                    <a href="{{ $invoiceUrl }}" style="display:inline-block;padding:12px 16px;background:#c99a2e;color:#172033;text-decoration:none;border-radius:8px;font-weight:bold;">Lihat Invoice</a>
                    <a href="{{ $confirmUrl }}" style="display:inline-block;padding:12px 16px;background:#10243f;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;margin-left:8px;">Konfirmasi Pembayaran</a>
                </p>

                <p style="margin-bottom:0;color:#667085;font-size:13px;">Email ini dikirim otomatis oleh sistem EMKO Indonesia.</p>
            </td>
        </tr>
    </table>
</div>

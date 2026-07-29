<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MidtransService
{
    public function isConfigured(): bool
    {
        return filled(config('emko.midtrans.server_key')) && filled(config('emko.midtrans.client_key'));
    }

    public function createSnapTransaction(Order $order): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Konfigurasi Midtrans belum lengkap.');
        }

        $order->loadMissing('product');

        $midtransOrderId = $order->midtrans_order_id ?: $order->invoice_number . '-' . Str::upper(Str::random(4));
        $grossAmount = (int) round($order->total_idr);

        $payload = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->email,
                'phone' => $order->phone,
                'shipping_address' => [
                    'first_name' => $order->customer_name,
                    'phone' => $order->phone,
                    'address' => $order->shipping_address,
                    'country_code' => 'IDN',
                ],
            ],
            'item_details' => [[
                'id' => (string) $order->product_id,
                'price' => $grossAmount,
                'quantity' => 1,
                'name' => Str::limit($order->product->product_name . ' x ' . $order->quantity, 50, ''),
            ]],
            'callbacks' => [
                'finish' => route('checkout.invoice', $order),
            ],
            'expiry' => [
                'unit' => config('emko.midtrans.expiry_unit', 'hour'),
                'duration' => (int) config('emko.midtrans.expiry_duration', 24),
            ],
        ];

        $response = Http::withBasicAuth(config('emko.midtrans.server_key'), '')
            ->withHeaders([
                'X-Append-Notification' => route('midtrans.notification'),
            ])
            ->acceptJson()
            ->asJson()
            ->post($this->snapEndpoint(), $payload);

        if ($response->failed()) {
            throw new RuntimeException('Gagal membuat transaksi Midtrans: ' . $response->body());
        }

        $result = $response->json();

        $order->update([
            'midtrans_order_id' => $midtransOrderId,
            'midtrans_snap_token' => $result['token'] ?? null,
            'midtrans_redirect_url' => $result['redirect_url'] ?? null,
            'payment_method' => 'midtrans',
            'midtrans_payload' => $payload,
        ]);

        return $result;
    }

    public function validSignature(array $payload): bool
    {
        $signature = $payload['signature_key'] ?? null;
        if (! $signature) {
            return false;
        }

        $raw = ($payload['order_id'] ?? '') . ($payload['status_code'] ?? '') . ($payload['gross_amount'] ?? '') . config('emko.midtrans.server_key');

        return hash_equals(hash('sha512', $raw), $signature);
    }

    public function applyNotification(Order $order, array $payload): void
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        $status = match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge' ? 'payment_confirmation_sent' : 'payment_verified',
            'settlement' => 'payment_verified',
            'pending' => 'pending_payment',
            'deny', 'cancel', 'expire', 'failure' => 'payment_rejected',
            default => $order->status,
        };

        $order->update([
            'status' => $status,
            'paid_at' => in_array($status, ['payment_verified', 'payment_confirmation_sent'], true) ? ($order->paid_at ?: now()) : $order->paid_at,
            'midtrans_transaction_id' => $payload['transaction_id'] ?? $order->midtrans_transaction_id,
            'midtrans_payment_type' => $payload['payment_type'] ?? $order->midtrans_payment_type,
            'midtrans_transaction_status' => $transactionStatus,
            'midtrans_fraud_status' => $fraudStatus,
            'midtrans_payload' => $payload,
            'notes' => trim(($order->notes ? $order->notes . "\n\n" : '') . 'Midtrans notification: ' . ($transactionStatus ?: '-') . ' | ' . ($payload['payment_type'] ?? '-') . ' | ' . now()->format('Y-m-d H:i:s')),
        ]);
    }

    private function snapEndpoint(): string
    {
        $base = config('emko.midtrans.is_production')
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';

        return $base . '/snap/v1/transactions';
    }
}

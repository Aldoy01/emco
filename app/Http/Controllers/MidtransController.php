<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function pay(Request $request, Order $order, MidtransService $midtrans)
    {
        $this->authorizeCustomerOrder($request, $order);

        if ($order->status === 'payment_verified') {
            return redirect()->route('checkout.invoice', $order)->with('success', 'Pembayaran invoice ini sudah terverifikasi.');
        }

        if (! $order->midtrans_redirect_url) {
            try {
                $midtrans->createSnapTransaction($order);
                $order->refresh();
            } catch (\Throwable $exception) {
                report($exception);
                return redirect()->route('checkout.invoice', $order)->with('error', 'Payment gateway belum bisa diproses. Silakan gunakan transfer manual atau hubungi sales.');
            }
        }

        return redirect()->away($order->midtrans_redirect_url);
    }

    public function notification(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();

        if (! $midtrans->validSignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('midtrans_order_id', $payload['order_id'] ?? null)->first();
        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $midtrans->applyNotification($order, $payload);

        return response()->json(['message' => 'OK']);
    }

    private function authorizeCustomerOrder(Request $request, Order $order): void
    {
        if ($request->user()->isAdmin()) {
            return;
        }

        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);
    }
}

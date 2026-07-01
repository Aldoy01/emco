<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['product', 'user'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->q;
                $query->where(function ($builder) use ($search) {
                    $builder->where('invoice_number', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => Order::STATUSES,
            'confirmationCount' => Order::where('status', 'payment_confirmation_sent')->count(),
            'pendingCount' => Order::where('status', 'pending_payment')->count(),
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load(['product', 'user']),
            'statuses' => Order::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUSES)),
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $notes = trim($order->notes ?? '');
        if (! empty($data['admin_notes'])) {
            $notes .= ($notes ? "\n\n" : '') . 'Catatan admin ' . now()->format('d-m-Y H:i') . ': ' . $data['admin_notes'];
        }

        $order->update([
            'status' => $data['status'],
            'notes' => $notes ?: null,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status invoice berhasil diperbarui.');
    }

    public function proof(Order $order)
    {
        abort_unless($order->payment_proof, 404);

        $path = public_path($order->payment_proof);
        abort_unless(File::exists($path), 404, 'File bukti pembayaran tidak ditemukan.');

        return response()->file($path);
    }
}

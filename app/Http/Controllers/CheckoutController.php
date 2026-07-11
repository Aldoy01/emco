<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\FinanceSettingController;
use App\Mail\OrderInvoiceCreated;
use App\Models\ContentSetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class CheckoutController extends Controller
{
    public function create(Request $request, Product $product)
    {
        abort_unless($product->is_purchasable, 404);

        $quantity = max(1, min(999, (int) $request->query('qty', 1)));

        return view('pages.checkout.create', [
            'product' => $product,
            'user' => $request->user(),
            'quantity' => $quantity,
            'unitPrice' => $product->final_price_idr,
            'originalUnitPrice' => $product->price_idr,
            'subtotal' => $product->final_price_idr * $quantity,
            'originalSubtotal' => $product->price_idr * $quantity,
        ]);
    }

    public function login(Request $request, Product $product)
    {
        $credentials = $request->validate([
            'login_email' => 'required|email',
            'login_password' => 'required|string',
        ]);

        if (! Auth::attempt(['email' => $credentials['login_email'], 'password' => $credentials['login_password']], $request->boolean('remember'))) {
            return back()->withErrors(['login_email' => 'Email atau password tidak sesuai.'])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('checkout.create', $product)->with('success', 'Anda sudah masuk. Silakan lanjutkan checkout.');
    }

    public function store(Request $request, Product $product)
    {
        abort_unless($product->is_purchasable, 404);

        $rules = [
            'customer_name' => 'required|string|max:160',
            'company' => 'nullable|string|max:160',
            'email' => ['required', 'email', 'max:160'],
            'phone' => 'required|string|max:60',
            'shipping_address' => 'required|string|max:1200',
            'country' => 'required|string|in:Indonesia',
            'city' => 'required|string|max:120',
            'province' => 'required|string|max:120',
            'postal_code' => 'nullable|string|max:30',
            'quantity' => 'required|integer|min:1|max:999',
            'notes' => 'nullable|string|max:1500',
        ];

        if (! $request->user()) {
            $rules['email'][] = Rule::unique('users', 'email');
            $rules['password'] = ['required', 'string', Password::defaults(), 'confirmed'];
        }

        $data = $request->validate($rules);

        $user = $request->user();
        if (! $user) {
            $user = User::create([
                'name' => $data['customer_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_admin' => false,
            ]);

            Auth::login($user);
            $request->session()->regenerate();
        }

        $quantity = (int) $data['quantity'];
        $unitPrice = $product->final_price_idr;
        $subtotal = $unitPrice * $quantity;
        $shippingCost = 0;
        $address = trim($data['shipping_address'] . "\n" . $data['city'] . ', ' . $data['province'] . ($data['postal_code'] ? ' ' . $data['postal_code'] : '') . "\n" . $data['country']);

        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'invoice_number' => $this->invoiceNumber(),
            'customer_name' => $data['customer_name'],
            'company' => $data['company'] ?? null,
            'email' => $user->email,
            'phone' => $data['phone'],
            'shipping_address' => $address,
            'quantity' => $quantity,
            'unit_price_idr' => $unitPrice,
            'subtotal_idr' => $subtotal,
            'shipping_cost_idr' => $shippingCost,
            'total_idr' => $subtotal + $shippingCost,
            'payment_method' => 'bank_transfer',
            'status' => 'pending_payment',
            'notes' => $data['notes'] ?? null,
        ]);

        $this->sendInvoiceEmail($order);

        return redirect()->route('checkout.invoice', $order)->with('success', 'Invoice berhasil dibuat. Instruksi pembayaran tersedia di halaman ini dan dikirim ke email customer jika konfigurasi email aktif.');
    }

    public function invoice(Request $request, Order $order)
    {
        $this->authorizeCustomerOrder($request, $order);

        return view('pages.checkout.invoice', [
            'order' => $order->load('product'),
            'finance' => ContentSetting::getValue('finance', FinanceSettingController::defaults()),
        ]);
    }

    public function confirm(Request $request, Order $order)
    {
        $this->authorizeCustomerOrder($request, $order);

        return view('pages.checkout.confirm', ['order' => $order->load('product')]);
    }

    public function storeConfirmation(Request $request, Order $order)
    {
        $this->authorizeCustomerOrder($request, $order);

        $data = $request->validate([
            'invoice_number' => ['required', 'string', 'max:80', Rule::in([$order->invoice_number])],
            'payer_name' => 'required|string|max:160',
            'bank_name' => 'required|string|max:120',
            'transfer_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:3072',
        ], [
            'invoice_number.in' => 'Nomor invoice tidak sesuai dengan invoice yang sedang dikonfirmasi.',
        ]);

        $proofPath = $order->payment_proof;
        if ($request->hasFile('payment_proof')) {
            if ($proofPath) {
                $oldPath = public_path($proofPath);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $directory = public_path('uploads/payments');
            File::ensureDirectoryExists($directory);
            $file = $request->file('payment_proof');
            $filename = Str::slug($order->invoice_number . '-' . $data['payer_name']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $proofPath = 'uploads/payments/' . $filename;
        }

        $order->update([
            'status' => 'payment_confirmation_sent',
            'payment_proof' => $proofPath,
            'paid_at' => now(),
            'notes' => trim(($order->notes ? $order->notes . "\n\n" : '') . 'Konfirmasi pembayaran invoice ' . $data['invoice_number'] . ': ' . $data['payer_name'] . ' | ' . $data['bank_name'] . ' | ' . $data['transfer_date'] . ' | Rp ' . number_format((float) $data['amount'], 0, ',', '.')),
        ]);

        return redirect()->route('checkout.invoice', $order)->with('success', 'Konfirmasi pembayaran berhasil dikirim. Admin akan memverifikasi pembayaran dan menghubungi Anda untuk pengiriman.');
    }

    private function authorizeCustomerOrder(Request $request, Order $order): void
    {
        if ($request->user()->isAdmin()) {
            return;
        }

        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);
    }

    private function invoiceNumber(): string
    {
        do {
            $number = 'INV-EMKO-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Order::where('invoice_number', $number)->exists());

        return $number;
    }

    private function sendInvoiceEmail(Order $order): void
    {
        try {
            Mail::to($order->email)->send(new OrderInvoiceCreated(
                $order->loadMissing('product'),
                ContentSetting::getValue('finance', FinanceSettingController::defaults())
            ));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}

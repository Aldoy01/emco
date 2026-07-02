<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminTwoFactorController extends Controller
{
    public function show(Request $request)
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403);

        if (! $request->session()->has('admin_2fa_hash')) {
            $this->sendCode($request);
        }

        return view('auth.admin-two-factor', [
            'debugCode' => config('app.debug') ? $request->session()->get('admin_2fa_plain') : null,
        ]);
    }

    public function verify(Request $request)
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403);

        $data = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $expiresAt = $request->session()->get('admin_2fa_expires_at');
        $hash = $request->session()->get('admin_2fa_hash');

        if (! $hash || ! $expiresAt || now()->greaterThan($expiresAt) || ! Hash::check($data['code'], $hash)) {
            LoginAudit::create([
                'email' => $request->user()->email,
                'user_id' => $request->user()->id,
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
                'status' => 'admin_2fa_failed',
                'context' => 'admin',
            ]);

            return back()->withErrors(['code' => 'Kode verifikasi tidak valid atau sudah kedaluwarsa.']);
        }

        $request->session()->put('admin_2fa_passed', true);
        $request->session()->forget(['admin_2fa_hash', 'admin_2fa_expires_at', 'admin_2fa_plain']);

        LoginAudit::create([
            'email' => $request->user()->email,
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'status' => 'admin_2fa_passed',
            'context' => 'admin',
        ]);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function resend(Request $request)
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403);

        $this->sendCode($request);

        return back()->with('status', 'Kode verifikasi baru sudah dikirim.');
    }

    private function sendCode(Request $request): void
    {
        $code = (string) random_int(100000, 999999);

        $request->session()->put('admin_2fa_hash', Hash::make($code));
        $request->session()->put('admin_2fa_expires_at', now()->addMinutes(10));
        if (config('app.debug')) {
            $request->session()->put('admin_2fa_plain', $code);
        }

        try {
            Mail::raw("Kode login admin EMCO Anda: {$code}\n\nKode berlaku 10 menit.", function ($message) use ($request) {
                $message->to($request->user()->email)->subject('Kode Login Admin EMCO');
            });
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}

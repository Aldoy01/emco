<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAudit;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        return auth()->user() && auth()->user()->isAdmin() ? route('admin.dashboard') : route('member.dashboard');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        LoginAudit::create([
            'email' => $user->email,
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'status' => 'login_success',
            'context' => $user->isAdmin() ? 'admin' : 'member',
        ]);

        if ($user->isAdmin()) {
            $request->session()->put('admin_2fa_passed', false);

            return redirect()->route('admin.two-factor.show');
        }

        return null;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        LoginAudit::create([
            'email' => $request->input($this->username()),
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'status' => 'login_failed',
            'context' => 'web',
        ]);

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_2fa_passed', 'admin_2fa_hash', 'admin_2fa_expires_at', 'admin_2fa_plain']);

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }
}

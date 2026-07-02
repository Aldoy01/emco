<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminTwoFactor
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->isAdmin() && ! $request->session()->get('admin_2fa_passed')) {
            return redirect()->route('admin.two-factor.show');
        }

        return $next($request);
    }
}

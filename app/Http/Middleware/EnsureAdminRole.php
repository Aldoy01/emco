<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin() || ! $user->hasAdminRole($roles)) {
            return redirect()->route('admin.dashboard')->with('error', 'Akses menu ini hanya untuk role admin yang diizinkan.');
        }

        return $next($request);
    }
}
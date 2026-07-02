<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAudit;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $audits = LoginAudit::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('email', 'like', $term)
                        ->orWhere('ip_address', 'like', $term)
                        ->orWhere('context', 'like', $term);
                });
            })
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.security.index', [
            'audits' => $audits,
            'statuses' => LoginAudit::query()->select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}

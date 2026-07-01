<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\QuotationRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $statusLabels = QuotationRequest::STATUSES;
        $statusCounts = QuotationRequest::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pipeline = collect($statusLabels)->map(function ($label, $key) use ($statusCounts) {
            return [
                'key' => $key,
                'label' => $label,
                'total' => (int) ($statusCounts[$key] ?? 0),
            ];
        })->values();

        $totalRfqs = max(QuotationRequest::count(), 1);
        $wonRfqs = (int) ($statusCounts['closed_won'] ?? 0);
        $conversionRate = round(($wonRfqs / $totalRfqs) * 100);

        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'newRfqs' => QuotationRequest::where('status', 'new')->count(),
            'openRfqs' => QuotationRequest::whereNotIn('status', ['closed_won', 'closed_lost'])->count(),
            'totalRfqs' => QuotationRequest::count(),
            'conversionRate' => $conversionRate,
            'pipeline' => $pipeline,
            'recentRfqs' => QuotationRequest::with(['lead', 'product'])->latest()->take(8)->get(),
        ]);
    }
}
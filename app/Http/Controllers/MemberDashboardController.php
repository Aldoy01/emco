<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('product')->latest()->paginate(10);
        $totalOrders = $request->user()->orders()->count();
        $pendingOrders = $request->user()->orders()->where('status', 'pending_payment')->count();
        $paidOrders = $request->user()->orders()->where('status', 'payment_confirmation_sent')->count();

        return view('member.dashboard', compact('orders', 'totalOrders', 'pendingOrders', 'paidOrders'));
    }
}
<?php
namespace App\Http\Controllers;

use App\Mail\SalesLeadReceived;
use App\Models\Lead;
use App\Models\Product;
use App\Models\QuotationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QuotationController extends Controller
{
    public function create(Request $request)
    {
        return view('pages.quotation', [
            'products' => Product::orderBy('product_name')->get(),
            'selectedProduct' => $request->product,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'company' => 'required|string|max:160',
            'email' => 'required|email|max:160',
            'phone' => 'required|string|max:40',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'project_location' => 'required|string|max:180',
            'technical_needs' => 'nullable|string|max:2000',
            'project_deadline' => 'nullable|date',
            'website' => 'nullable|size:0',
        ]);

        $lead = Lead::firstOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name'], 'company' => $data['company'], 'phone' => $data['phone'], 'source' => 'Website Hubungi Sales']
        );

        $lead->update([
            'name' => $data['name'],
            'company' => $data['company'],
            'phone' => $data['phone'],
        ]);

        $quotation = QuotationRequest::create([
            'lead_id' => $lead->id,
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'project_location' => $data['project_location'],
            'technical_needs' => $data['technical_needs'] ?? null,
            'project_deadline' => $data['project_deadline'] ?? null,
            'status' => 'new',
            'utm_source' => $request->query('utm_source'),
            'utm_campaign' => $request->query('utm_campaign'),
            'ip_address' => $request->ip(),
        ])->load(['lead', 'product']);

        try {
            Mail::to(config('emko.sales_email'))->send(new SalesLeadReceived($quotation));
        } catch (\Throwable $exception) {
            Log::warning('Sales lead email failed to send.', [
                'quotation_id' => $quotation->id,
                'to' => config('emko.sales_email'),
                'error' => $exception->getMessage(),
            ]);
        }

        return redirect()->route('quotation.create')->with('success', 'Permintaan penawaran berhasil dikirim. Tim sales akan menghubungi Anda.');
    }
}
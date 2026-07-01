<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\QuotationRequest;
use Illuminate\Http\Request;
class QuotationController extends Controller {
    public function index(){ return view('admin.quotations.index', ['quotations'=>QuotationRequest::with(['lead','product'])->latest()->paginate(20), 'statuses'=>QuotationRequest::STATUSES]); }
    public function show(QuotationRequest $quotation){ return view('admin.quotations.show', ['quotation'=>$quotation->load(['lead','product']), 'statuses'=>QuotationRequest::STATUSES]); }
    public function update(Request $request, QuotationRequest $quotation){ $data=$request->validate(['status'=>'required|in:'.implode(',',array_keys(QuotationRequest::STATUSES)), 'follow_up_notes'=>'nullable|string|max:3000']); $quotation->update($data); return back()->with('success','Status RFQ berhasil diperbarui.'); }
    public function export(){
        $rows = QuotationRequest::with(['lead','product'])->latest()->get();
        return response()->stream(function() use ($rows){ $out=fopen('php://output','w'); fputcsv($out,['Tanggal','Status','Nama','Perusahaan','Email','Telepon','Produk','Qty','Lokasi','Kebutuhan Teknis']); foreach($rows as $r){ fputcsv($out,[$r->created_at,$r->status,$r->lead->name,$r->lead->company,$r->lead->email,$r->lead->phone,$r->product->product_name,$r->quantity,$r->project_location,$r->technical_needs]); } fclose($out); }, 200, ['Content-Type'=>'text/csv','Content-Disposition'=>'attachment; filename="emko-rfq.csv"']);
    }
}

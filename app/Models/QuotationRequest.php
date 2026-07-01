<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class QuotationRequest extends Model {
    use HasFactory;
    public const STATUSES = ['new'=>'New','contacted'=>'Contacted','quotation_sent'=>'Quotation Sent','negotiation'=>'Negotiation','closed_won'=>'Closed Won','closed_lost'=>'Closed Lost'];
    protected $fillable = ['lead_id','product_id','quantity','project_location','technical_needs','project_deadline','status','follow_up_notes','utm_source','utm_campaign','ip_address'];
    protected $casts = ['project_deadline'=>'date'];
    public function lead(){ return $this->belongsTo(Lead::class); }
    public function product(){ return $this->belongsTo(Product::class); }
}

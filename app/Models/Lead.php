<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Lead extends Model {
    use HasFactory;
    protected $fillable = ['name','company','email','phone','source'];
    public function quotationRequests(){ return $this->hasMany(QuotationRequest::class); }
}

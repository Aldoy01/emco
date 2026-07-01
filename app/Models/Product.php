<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','product_code','slug','product_name','short_description','features','specifications','price_usd','discount_percent','final_price_usd','price_note','datasheet_file','image','status','is_featured'];
    protected $casts = ['features'=>'array','specifications'=>'array','price_usd'=>'decimal:2','discount_percent'=>'decimal:2','final_price_usd'=>'decimal:2','is_featured'=>'boolean'];
    protected $appends = ['price_idr','final_price_idr','formatted_price_idr','formatted_final_price_idr'];

    public function category(){ return $this->belongsTo(Category::class); }
    public function quotations(){ return $this->hasMany(QuotationRequest::class); }

    public function getPriceIdrAttribute(): int
    {
        return (int) round(((float) $this->price_usd) * config('emko.usd_to_idr_rate', 16000));
    }

    public function getFinalPriceIdrAttribute(): int
    {
        return (int) round(((float) $this->final_price_usd) * config('emko.usd_to_idr_rate', 16000));
    }

    public function getFormattedPriceIdrAttribute(): string
    {
        return 'Rp ' . number_format($this->price_idr, 0, ',', '.');
    }

    public function getFormattedFinalPriceIdrAttribute(): string
    {
        return 'Rp ' . number_format($this->final_price_idr, 0, ',', '.');
    }
}
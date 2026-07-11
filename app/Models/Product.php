<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','product_code','slug','product_name','short_description','features','specifications','purchase_information','price_usd','discount_percent','final_price_usd','price_note','datasheet_file','image','status','is_featured'];
    protected $casts = ['features'=>'array','specifications'=>'array','price_usd'=>'decimal:2','discount_percent'=>'decimal:2','final_price_usd'=>'decimal:2','is_featured'=>'boolean'];
    protected $appends = ['price_idr','final_price_idr','formatted_price_idr','formatted_final_price_idr','status_label','is_purchasable'];

    public function category(){ return $this->belongsTo(Category::class); }
    public function quotations(){ return $this->hasMany(QuotationRequest::class); }

    public function getPriceIdrAttribute(): int
    {
        return (int) round((float) $this->price_usd);
    }

    public function getFinalPriceIdrAttribute(): int
    {
        return (int) round((float) $this->final_price_usd);
    }

    public function getFormattedPriceIdrAttribute(): string
    {
        if ($this->price_idr <= 0) {
            return '';
        }

        return 'Rp ' . number_format($this->price_idr, 0, ',', '.');
    }

    public function getFormattedFinalPriceIdrAttribute(): string
    {
        if ($this->final_price_idr <= 0) {
            return '';
        }

        return 'Rp ' . number_format($this->final_price_idr, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'active' => 'Tersedia',
            'by_request' => 'By Request',
            'inactive' => 'Tidak Aktif',
            'discontinued' => 'Discontinued',
        ][$this->status] ?? ucwords(str_replace('_', ' ', (string) $this->status));
    }

    public function getIsPurchasableAttribute(): bool
    {
        return $this->status === 'active';
    }
}

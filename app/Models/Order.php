<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending_payment' => 'Menunggu Pembayaran',
        'payment_confirmation_sent' => 'Konfirmasi Masuk',
        'payment_verified' => 'Pembayaran Terverifikasi',
        'payment_rejected' => 'Pembayaran Ditolak',
        'processing' => 'Pesanan Diproses',
        'shipped' => 'Pesanan Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    protected $fillable = [
        'user_id', 'product_id', 'invoice_number', 'customer_name', 'company', 'email', 'phone',
        'shipping_address', 'quantity', 'unit_price_idr', 'subtotal_idr', 'original_subtotal_idr',
        'discount_idr', 'tax_percent', 'tax_idr', 'shipping_cost_idr', 'total_idr', 'payment_method',
        'status', 'notes', 'payment_proof', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'tax_percent' => 'float',
    ];

    public function getRouteKeyName(): string
    {
        return 'invoice_number';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return $this->rupiah($this->unit_price_idr);
    }

    public function getFormattedOriginalSubtotalAttribute(): string
    {
        return $this->rupiah($this->original_subtotal_idr ?: $this->subtotal_idr);
    }

    public function getFormattedDiscountAttribute(): string
    {
        return $this->rupiah($this->discount_idr);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return $this->rupiah($this->subtotal_idr);
    }

    public function getTaxLabelAttribute(): string
    {
        return 'PPN ' . rtrim(rtrim(number_format($this->tax_percent, 2, ',', '.'), '0'), ',') . '%';
    }

    public function getFormattedTaxAttribute(): string
    {
        return $this->rupiah($this->tax_idr);
    }

    public function getFormattedShippingCostAttribute(): string
    {
        return $this->rupiah($this->shipping_cost_idr);
    }

    public function getFormattedTotalAttribute(): string
    {
        return $this->rupiah($this->total_idr);
    }

    private function rupiah(int|float|null $amount): string
    {
        return 'Rp ' . number_format((float) ($amount ?? 0), 0, ',', '.');
    }
}
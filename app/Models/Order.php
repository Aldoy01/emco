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
        'shipping_address', 'quantity', 'unit_price_idr', 'subtotal_idr', 'shipping_cost_idr',
        'total_idr', 'payment_method', 'status', 'notes', 'payment_proof', 'paid_at'
    ];

    protected $casts = ['paid_at' => 'datetime'];

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
        return 'Rp ' . number_format($this->unit_price_idr, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal_idr, 0, ',', '.');
    }

    public function getFormattedShippingCostAttribute(): string
    {
        return 'Rp ' . number_format($this->shipping_cost_idr, 0, ',', '.');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_idr, 0, ',', '.');
    }
}

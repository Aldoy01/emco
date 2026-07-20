<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $previousStatusLabel;
    public string $newStatusLabel;
    public ?string $adminNote;

    public function __construct(Order $order, string $previousStatusLabel, string $newStatusLabel, ?string $adminNote = null)
    {
        $this->order = $order;
        $this->previousStatusLabel = $previousStatusLabel;
        $this->newStatusLabel = $newStatusLabel;
        $this->adminNote = $adminNote;
    }

    public function build()
    {
        return $this->subject('Update Status Pembelian EMKO - ' . $this->order->invoice_number)
            ->view('emails.order-status-updated');
    }
}
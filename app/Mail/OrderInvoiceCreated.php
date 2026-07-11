<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public array $finance;

    public function __construct(Order $order, array $finance)
    {
        $this->order = $order;
        $this->finance = $finance;
    }

    public function build()
    {
        return $this->subject('Invoice Pembelian EMKO - ' . $this->order->invoice_number)
            ->view('emails.order-invoice');
    }
}

<?php

namespace App\Mail;

use App\Models\QuotationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalesLeadReceived extends Mailable
{
    use Queueable, SerializesModels;

    public QuotationRequest $quotation;

    public function __construct(QuotationRequest $quotation)
    {
        $this->quotation = $quotation;
    }

    public function build()
    {
        return $this->subject('Lead Baru EMKO - ' . $this->quotation->product->product_name)
            ->view('emails.sales-lead');
    }
}
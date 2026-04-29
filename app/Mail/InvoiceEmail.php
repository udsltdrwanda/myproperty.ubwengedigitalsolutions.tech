<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
class InvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $paidAmount;
    public $balance;
    public $landlord;

    public function __construct(Invoice $invoice, $paidAmount, $balance, $landlord)
    {
        $this->invoice = $invoice;
        $this->paidAmount = $paidAmount;
        $this->balance = $balance;
        $this->landlord = $landlord;
    }

    public function build()
    {
        $pdf = Pdf::loadView('livewire.user.land-lord.invoice.pdf-invoice', [
            'invoice' => $this->invoice,
            'paidAmount' => $this->paidAmount,
            'balance' => $this->balance,
            'landlord' => $this->landlord,
        ]);

        return $this->subject('Invoice #' . $this->invoice->invoice_no)
            ->html('<p>Please find your invoice attached.</p>')
            ->attachData($pdf->output(), 'invoice_' . $this->invoice->invoice_no . '.pdf', ['mime' => 'application/pdf']);
    }
}

<?php

namespace App\Livewire\User\LandLord\Invoice;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\PaymentMode;
use App\Models\PaymentRecords;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class InvoiceDetailLivewire extends Component
{
    use WithFileUploads;

    public $payment_proof;
    public $invoice;
    public $showPaymentModal = false;
    public $payed_amount;
    public $payment_mode;
    public $payment_modes;
    public $payment_date;
    public $payment_reason;
    public $totalDue;
    public $totalPaid;
    public $landlord;

    public function mount($invoice)
    {
        $this->loadInvoiceData($invoice);
    }

    private function loadInvoiceData($invoiceId)
    {
        $this->invoice = Invoice::with(['tenant', 'unit', 'payments.paymentMode', 'property'])->findOrFail($invoiceId);
        $this->payment_modes = PaymentMode::where('landlord_id', Auth::user()->landlord_id)->get();

        // Calculate total due
        $this->totalDue = $this->invoice->amount + $this->invoice->vat;

        // Calculate total paid
        $this->totalPaid = $this->invoice->payments->sum('payed_amount');

        // Set default payment amount to remaining balance
        $this->payed_amount = max(0, $this->totalDue - $this->totalPaid);

        $this->landlord = User::where('landlord_id', $this->invoice->landlord_id)->first();
        $this->payment_date = now()->format('Y-m-d');
    }

    public function showPaymentForm()
    {
        // Set default amount to remaining balance
        $this->payed_amount = max(0, $this->totalDue - $this->totalPaid);
        $this->reset(['payment_mode', 'payment_reason', 'payment_proof']);
        $this->payment_date = now()->format('Y-m-d');
        $this->showPaymentModal = true;
    }

    public function makePayment()
    {
        $this->validate([
            'payed_amount' => 'required|numeric|min:1|max:' . max(0, $this->totalDue - $this->totalPaid),
            'payment_mode' => 'required',
            'payment_date' => 'required|date',
            'payment_reason' => 'nullable|string',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $documentUrl = null;
        if ($this->payment_proof && !is_string($this->payment_proof)) {
            $proofPath = $this->payment_proof->store('payment_proofs', 'public');
            $documentUrl = asset('storage/' . $proofPath);
        }

        PaymentRecords::create([
            'invoice_no' => $this->invoice->invoice_no,
            'user_id' => Auth::id(),
            'payed_amount' => $this->payed_amount,
            'payment_mode' => $this->payment_mode,
            'payment_date' => $this->payment_date,
            'payment_reason' => $this->payment_reason,
            'payment_proof' => $documentUrl,
        ]);

        // Update invoice status based on payments
        $this->updateInvoiceStatus();

        $this->showPaymentModal = false;
        $this->dispatch('show-success-message', message: 'Payment recorded successfully!');
    }

    private function updateInvoiceStatus()
    {
        // Reload the invoice to get fresh data including the new payment
        $this->loadInvoiceData($this->invoice->id);

        // Calculate the balance due
        $balanceDue = $this->totalDue - $this->totalPaid;

        // Update invoice status based on total payments vs total due
        $status = 'Pending';

        if ($balanceDue <= 0) {
            $status = 'Paid';
        } elseif ($this->totalPaid > 0) {
            $status = 'Partial';
        }

        $this->invoice->update([
            'invoice_status' => $status,
        ]);
    }

    public function render()
    {
        return view('livewire.user.land-lord.invoice.invoice-detail-livewire');
    }
}

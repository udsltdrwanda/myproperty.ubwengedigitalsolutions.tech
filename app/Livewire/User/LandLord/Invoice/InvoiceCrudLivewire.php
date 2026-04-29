<?php

namespace App\Livewire\User\LandLord\Invoice;

use App\Models\House;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class InvoiceCrudLivewire extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    public $invoices;
    public $invoice_id;
    public $invoice_no;
    public $amount;
    public $rentTypes;
    public $property_id;
    public $unit_id;
    public $tenant_id;
    public $house_id;
    public $properties = [];
    public $units = [];
    public $houses = [];
    public $tenants = [];
    public $unit_rent;
    public $isOpen = false;
    public $deleteModal = false;
    public $total_amount = 0;
    public $invoiceToDelete;
    public $start_date;
    public $end_date;
    public $durationUnits;
    public $duration;
    public $vat = 0;

    // Added for filtering
    public $status_filter = '';
    public $per_page = 10;
    public $statusOptions = ['Pending', 'Canceled', 'Partial', 'Paid'];

    // EbmInvoice properties
    public $showEbmInvoiceModal = false;
    public $currentInvoiceId;
    public $ebmInvoiceNo;
    public $ebmInvoiceDescription;
    public $ebmInvoiceAttachment;
    public $ebmInvoiceStatus = 'pending';

    protected function rules()
    {
        return [
            'invoice_no' => 'required|string|unique:invoices,invoice_no,' . $this->invoice_id,
            'amount' => 'required|numeric|min:0',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:property_units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function mount()
    {
        $this->properties = Property::where('user_id', Auth::id())->get();
        $this->tenants = Tenant::where('landlord_id', Auth::user()->landlord_id)->get();
    }

    // Updated to use pagination instead of loading all invoices into a property
    public function loadInvoices()
    {
        $this->tenants = Tenant::where('landlord_id', Auth::user()->landlord_id)->get();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedPropertyId($value)
    {
        $this->houses = House::where('property_id', $value)->get();
        $this->house_id = null;
        $this->units = [];
        $this->unit_id = null;
    }

    public function updatedHouseId($value)
    {
        $this->units = PropertyUnit::where('house_id', $value)->get();
        $this->unit_id = null;
    }

    public function updatedUnitId($value)
    {
        if ($value) {
            $unit = PropertyUnit::find($value);
            $this->unit_rent = $unit->rent;
            $this->amount = $unit->rent;
            $this->rentTypes = $unit->rentTypes;
            $this->calculateVat();
        } else {
            $this->unit_rent = null;
            $this->amount = null;
            $this->calculateVat();
        }
    }

    protected function calculateTotalAmount()
    {
        if (!$this->amount || !$this->start_date || !$this->end_date) {
            $this->total_amount = 0;
            $this->durationUnits = '';
            return;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        $amount = floatval($this->amount);
        $vat = floatval($this->vat);
        $total = 0;

        switch (strtolower($this->rentTypes)) {
            case 'monthly':
                $months = $start->diffInMonths($end) + 0;
                $this->durationUnits = 'Approx. ' . round($months) . ' Month' . ($months > 1 ? 's' : '');
                $this->duration = round($months);
                $total = round($months) * $amount;
                $totalvat = round($months) * $vat;
                break;

            case 'weekly':
                $weeks = $start->diffInWeeks($end) + 0;
                $this->durationUnits = 'Approx. ' . round($weeks) . ' Week' . ($weeks > 1 ? 's' : '');
                $total = round($weeks) * $amount;
                $this->duration = round($weeks);
                $totalvat = round($weeks) * $vat;
                break;

            case 'daily':
                $days = $start->diffInDays($end) + 0;
                $this->durationUnits = 'Approx. ' . round($days) . ' Day' . ($days > 1 ? 's' : '');
                $total = round($days) * $amount;
                $this->duration = round($days);
                $totalvat = round($days) * $vat;
                break;

            default:
                $total = $amount;
                $this->durationUnits = '';
                break;
        }

        $this->total_amount = round($total + $totalvat);
    }

    public function create()
    {
        $this->reset(['invoice_id', 'unit_id', 'tenant_id', 'property_id', 'house_id', 'amount', 'start_date', 'end_date', 'vat']);
        $this->resetInputFields();
        $this->generateInvoiceNumber();
        $this->tenants = Tenant::where('landlord_id', Auth::user()->landlord_id)->get();
        $this->openModal();
    }

    public function updatedAmount($value)
    {
        $this->calculateVat();
        $this->calculateTotalAmount();
    }

    public function updatedStartDate()
    {
        $this->calculateTotalAmount();
    }

    public function updatedEndDate()
    {
        $this->calculateTotalAmount();
    }

    public function updatedRentTypes()
    {
        $this->calculateTotalAmount();
    }

    protected function calculateVat()
    {
        $unit = PropertyUnit::find($this->unit_id);
        $amount = floatval($this->amount);

        if ($unit && strtolower($unit->type) === 'commercial') {
            $this->vat = round($amount * 0.18, 2);
        } else {
            $this->vat = 0;
        }
    }

    public function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $lastInvoice = Invoice::where('invoice_no', 'LIKE', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) str_replace($prefix, '', $lastInvoice->invoice_no);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $this->invoice_no = $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->invoice_id = null;
        $this->invoice_no = '';
        $this->amount = '';
        $this->property_id = null;
        $this->house_id = null;
        $this->unit_id = null;
        $this->tenant_id = null;
        $this->units = [];
        $this->houses = [];
        $this->tenants = [];
        $this->unit_rent = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        Invoice::updateOrCreate(
            ['id' => $this->invoice_id],
            [
                'user_id' => Auth::id(),
                'company_id' => Auth::user()->company_id ?? null,
                'property_id' => $this->property_id,
                'unit_id' => $this->unit_id,
                'tenant_id' => $this->tenant_id,
                'invoice_no' => $this->invoice_no,
                'amount' => $this->amount,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'vat' => $this->vat,
                'duration_units' => $this->duration,
            ],
        );

        $this->dispatch('show-success-message', message: $this->invoice_id ? 'Invoice Updated Successfully.' : 'Invoice Created Successfully.');

        $this->closeModal();
        $this->loadInvoices();
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->invoice_id = $id;
        $this->invoice_no = $invoice->invoice_no;
        $this->amount = $invoice->amount;
        $this->property_id = $invoice->property_id;
        $this->houses = House::where('property_id', $this->property_id)->get();
        $this->house_id = optional($invoice->unit->house)->id;
        $this->loadUnitsAndTenants();
        $this->unit_id = $invoice->unit_id;
        $this->tenant_id = $invoice->tenant_id;
        $this->vat = $invoice->vat;
        $this->start_date = Carbon::parse($invoice->start_date)->addDay()->format('Y-m-d');
        $this->end_date = Carbon::parse($invoice->end_date)->addDay()->format('Y-m-d');
        $unit = PropertyUnit::find($invoice->unit_id);
        $this->rentTypes = $unit->rentTypes ?? null;
        $this->calculateVat();
        $this->calculateTotalAmount();

        $this->openModal();
    }

    protected function loadUnitsAndTenants()
    {
        if ($this->house_id) {
            $this->units = PropertyUnit::where('house_id', $this->house_id)->get();
        }
    }

    public function confirmDelete($id)
    {
        $this->invoiceToDelete = $id;
        $this->deleteModal = true;
    }

    public function delete()
    {
        Invoice::find($this->invoiceToDelete)?->delete();
        $this->deleteModal = false;
        $this->loadInvoices();
        $this->dispatch('show-success-message', message: 'Invoice Deleted Successfully.');
    }

    public function generatePdf($id)
    {
        $invoice = Invoice::with(['property', 'unit', 'tenant', 'payments'])->findOrFail($id);
        $landlord = User::where('landlord_id', $invoice->landlord_id)->first();

        $invoiceData = [
            'invoice' => $invoice,
            'landlord' => $landlord,
            'paidAmount' => $invoice->payments->sum('amount'),
            'balance' => $invoice->amount - $invoice->payments->sum('amount'),
        ];

        $pdf = Pdf::loadView('livewire.user.land-lord.invoice.pdf-invoice', $invoiceData);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice-' . $invoice->invoice_no . '.pdf');
    }

    public function addEbmInvoice($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $this->currentInvoiceId = $invoiceId;
        $this->ebmInvoiceNo = $invoice->invoice_no;
        $this->ebmInvoiceDescription = '';
        $this->ebmInvoiceAttachment = null;
        $this->ebmInvoiceStatus = 'pending';
        $this->showEbmInvoiceModal = true;
    }

    public function viewEbmInvoice($invoiceId)
    {
        $invoice = Invoice::with('ebmInvoice')->findOrFail($invoiceId);
        $this->currentInvoiceId = $invoiceId;
        $this->ebmInvoiceNo = $invoice->invoice_no;
        $this->ebmInvoiceDescription = $invoice->ebmInvoice->invoice_description ?? '';
        $this->ebmInvoiceAttachment = $invoice->ebmInvoice->invoice_attachment ?? null;
        $this->ebmInvoiceStatus = $invoice->ebmInvoice->invoice_status ?? 'pending';
        $this->showEbmInvoiceModal = true;
    }

    public function saveEbmInvoice()
    {
        $this->validate([
            'ebmInvoiceDescription' => 'nullable|string',
            'ebmInvoiceAttachment' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
            'ebmInvoiceStatus' => 'required|in:pending,paid,canceled',
        ]);

        $invoice = Invoice::findOrFail($this->currentInvoiceId);

        // Handle file upload
        $attachmentPath = null;
        if ($this->ebmInvoiceAttachment) {
            $fileName = 'ebm_invoice_' . $invoice->invoice_no . '_' . time() . '.pdf';
            $attachmentPath = $this->ebmInvoiceAttachment->storeAs('ebm_invoices', $fileName, 'public');
        }

        $invoice->ebmInvoice()->updateOrCreate(
            ['invoice_no' => $invoice->invoice_no],
            [
                'invoice_description' => $this->ebmInvoiceDescription,
                'invoice_attachment' => $attachmentPath,
                'invoice_status' => $this->ebmInvoiceStatus,
            ]
        );

        $this->showEbmInvoiceModal = false;
        $this->dispatch('show-success-message', message: 'EBM Invoice saved successfully.');
    }

    public function downloadEbmInvoice($invoiceId)
    {
        $invoice = Invoice::with('ebmInvoice')->findOrFail($invoiceId);

        if (!$invoice->ebmInvoice || !$invoice->ebmInvoice->invoice_attachment) {
            $this->dispatch('show-error-message', message: 'No attachment found for this EBM Invoice.');
            return;
        }

        return Storage::disk('public')->download($invoice->ebmInvoice->invoice_attachment);
    }

    public function closeEbmInvoiceModal()
    {
        $this->showEbmInvoiceModal = false;
        $this->reset(['currentInvoiceId', 'ebmInvoiceNo', 'ebmInvoiceDescription', 'ebmInvoiceAttachment', 'ebmInvoiceStatus']);
    }

    public function render()
    {
        $query = Invoice::with(['property', 'unit.house', 'tenant', 'payments'])
            ->where('landlord_id', Auth::user()->landlord_id);

        if ($this->status_filter) {
            $query->where('invoice_status', $this->status_filter);
        }
        // Apply sorting
        $query->orderBy('created_at', 'desc');

        // Get paginated results
        $paginatedInvoices = $query->paginate($this->per_page);

        return view('livewire.user.land-lord.invoice.invoice-crud-livewire', [
            'paginatedInvoices' => $paginatedInvoices
        ]);
    }
}

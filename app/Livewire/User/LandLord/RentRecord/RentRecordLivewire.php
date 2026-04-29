<?php

namespace App\Livewire\User\Landlord\RentRecord;

use App\Models\RentRecord;
use App\Models\Tenant;
use App\Models\PropertyUnit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Invoice;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use App\Mail\InvoiceEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Contracts\Service\Attribute\Required;

class RentRecordLivewire extends Component
{
    use WithFileUploads;
    public $rentRecords;
    public $invoice_no;
    public $rentRecordId;
    public $user_id;
    public $house_name;
    public $unit_name;
    public $tenant_name;
    public $amount;
    public $vat;
    public $start_date;
    public $end_date;
    public $duration_time;
    public $agreement_document;
    public $status;
    public $isEdit = false;
    public $rentTypes;
    public $invoice_id;
    public $tenants = [];
    public $units = [];
    public $showFormModal = false;
    public $showDeleteModal = false;
    public $deleteId;
    public $showInvoiceModal = false;
    public $invoiceRecordId;
    public $showViewInvoiceModal = false;
    public $currentInvoice;
    public $showSendEmailModal = false;
    public $emailInvoiceId;
    public $durationUnits;
    public $houses = [];
    public $house_id;
    public $unit_rent;
    public $property_id;
    public $tenant_id;
    public $unit_id;
    public $invoiced_amount;
    public $invoiced_months;
    public $properties = [];
    public $isOpen = false;
    public $startDateFromLastRecord = false;

    // ADD THIS MISSING PROPERTY
    public $due_date;

    protected function rules()
    {
        return [
            'amount' => 'required|numeric|min:0',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:property_units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'invoiced_months' => 'required|integer|min:1',
            'invoiceRecordId' => 'required|exists:rent_records,id',
            'due_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function mount()
    {
        $this->houses = \App\Models\House::whereHas('property', function ($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })
            ->with('property')
            ->get();

        $this->units = PropertyUnit::whereHas('house.property', function ($query) {
            $query->where('landlord_id', Auth::user()->landlord_id);
        })->get();
        $this->fetchRecords();
        $this->properties = Property::where('landlord_id', Auth::user()->landlord_id)->get();
        $this->tenants = Tenant::where('landlord_id', Auth::user()->landlord_id)->get();
    }

    public function createInvoiceModel($id)
    {
        $record = RentRecord::findOrFail($id);
        $this->invoiceRecordId = $record->id;
        $this->property_id = $record->unit->house->property_id;
        $this->house_id = $record->unit->house->id;
        $this->unit_id = $record->unit->id;
        $this->tenant_id = $record->tenant->id;
        $this->amount = $record->amount;

        // Calculate remaining months and adjust dates
        $invoicedMonths = $this->getInvoicedMonths($id);
        $remainingMonths = $record->duration_time - $invoicedMonths;

        // Set start date to the end of the last invoice period
        $lastInvoice = Invoice::where('rent_record_id', $id)->latest('end_date')->first();
        if ($lastInvoice) {
            $this->start_date = Carbon::parse($lastInvoice->end_date)->addDay()->format('Y-m-d');
        } else {
            $this->start_date = $record->start_date;
        }

        // Set end date based on remaining months
        $this->end_date = Carbon::parse($this->start_date)->addMonths($remainingMonths)->format('Y-m-d');
        $this->duration_time = $remainingMonths;
        $this->rentTypes = $record->unit->rentTypes;

        // Set default due date (e.g., 30 days from start date)
        $this->due_date = Carbon::parse($this->start_date)->addDays(30)->format('Y-m-d');

        $this->calculateVat();
        $this->calculateDuration();
        $this->generateInvoiceNumber();
        $this->tenants = Tenant::where('landlord_id', Auth::user()->landlord_id)->get();

        $this->openModal();
    }

    public function updatedInvoicedMonths()
    {
        $this->calculateInvoicedAmount();
    }

    public function updatedAmount()
    {
        $this->calculateVat();
        $this->calculateInvoicedAmount();
    }

    public function updatedVat()
    {
        $this->calculateInvoicedAmount();
    }

    public function calculateInvoicedAmount()
    {
        if ($this->amount && $this->invoiced_months) {
            $this->invoiced_amount = ($this->amount + $this->vat) * $this->invoiced_months;
        } else {
            $this->invoiced_amount = 0;
        }
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
        $this->due_date = null; // ADD THIS LINE
        $this->resetErrorBag();
    }

    public function updatedHouseId($value)
    {
        if ($value) {
            $this->units = PropertyUnit::where('house_id', $value)->get();
            $this->unit_id = null;
        } else {
            $this->units = [];
        }
    }

    public function resetForm()
    {
        $this->reset(['rentRecordId', 'tenant_id', 'house_id', 'unit_id', 'amount', 'vat', 'start_date', 'end_date', 'duration_time', 'agreement_document', 'status', 'isEdit', 'rentTypes', 'startDateFromLastRecord', 'due_date']); // ADD due_date HERE
    }

    public function checkLastRentRecord()
    {
        $lastRecord = $this->getLastRentRecord();

        if ($lastRecord) {
            $this->start_date = Carbon::parse($lastRecord->end_date)->addDay()->format('Y-m-d');
        } else {
            $this->start_date = null;
            $this->startDateFromLastRecord = false;
        }
    }

    public function getLastRentRecord()
    {
        if (!$this->tenant_id || !$this->unit_id) {
            return null;
        }
        $lastRecord = RentRecord::where('tenant_id', $this->tenant_id)->where('unit_id', $this->unit_id)->latest('end_date')->first();

        if ($lastRecord) {
            $this->startDateFromLastRecord = true;
        } else {
            $this->startDateFromLastRecord = false;
        }

        return $lastRecord;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $record = RentRecord::findOrFail($id);
        $this->fill($record->toArray());

        if ($record->unit) {
            $this->house_id = $record->unit->house_id;
            $this->units = PropertyUnit::where('house_id', $this->house_id)->get();
        }

        $this->rentRecordId = $id;
        $this->isEdit = true;
        $this->showFormModal = true;
    }

    public function confirmSendEmail($invoiceId)
    {
        $this->emailInvoiceId = $invoiceId;
        $invoice = Invoice::with(['tenant'])->find($invoiceId);

        if (!$invoice) {
            $this->dispatch('show-error-message', message: 'Invoice not found!');
            return;
        }

        $this->showSendEmailModal = true;
    }

    public function sendInvoiceEmail()
    {
        $invoice = Invoice::with(['property', 'unit', 'tenant', 'payments'])->findOrFail($this->emailInvoiceId);

        $paidAmount = $invoice->payments->sum('amount');
        $balance = $invoice->amount - $paidAmount;
        $landlord = User::where('landlord_id', $invoice->landlord_id)->first();

        try {
            Mail::to($invoice->tenant->email)
                ->cc(Auth::user()->company_email)
                ->send(new InvoiceEmail($invoice, $paidAmount, $balance, $landlord));

            $invoice->update([
                'email_sent' => true,
                'email_sent_count' => $invoice->email_sent_count + 1,
                'last_email_sent_at' => now(),
            ]);

            $this->dispatch('show-success-message', message: 'Invoice sent (#' . $invoice->email_sent_count . ' time(s))');
        } catch (\Exception $e) {
            $this->dispatch('show-error-message', message: 'Failed to send email: ' . $e->getMessage());
        }

        $this->showSendEmailModal = false;
    }

    public function updatedTenantId($value)
    {
        if ($value && $this->unit_id) {
            $this->checkLastRentRecord();
        }
    }

    public function updatedUnitId($value)
    {
        if ($value) {
            $unit = PropertyUnit::find($value);
            if ($unit) {
                $this->amount = $unit->rent;
                $this->rentTypes = $unit->rentTypes;
                $this->calculateVat();
                $this->calculateDuration();
                $this->checkLastRentRecord();
                if ($this->tenant_id) {
                    $this->checkLastRentRecord();
                }
            }
        } else {
            $this->amount = null;
            $this->vat = 0;
        }
    }

    public function updatedStartDate()
    {
        $this->calculateDuration();
        // Update due date when start date changes
        if ($this->start_date && !$this->due_date) {
            $this->due_date = Carbon::parse(today())->addDays(10)->format('Y-m-d');
        }
    }

    public function updatedEndDate()
    {
        $this->calculateDuration();
    }

    protected function calculateVat()
    {
        $unit = PropertyUnit::find($this->unit_id);
        $amount = floatval($this->amount);

        $this->vat = $unit && strtolower($unit->type) === 'commercial' ? round($amount * 0.18, 2) : 0;
    }

    protected function calculateDuration()
    {
        if (!$this->start_date || !$this->end_date) {
            $this->duration_time = '';
            return;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        switch (strtolower($this->rentTypes)) {
            case 'monthly':
                $months = $start->diffInMonths($end);
                $this->duration_time = round($months);
                break;
            case 'weekly':
                $weeks = $start->diffInWeeks($end);
                $this->duration_time = round($weeks);
                break;
            case 'daily':
                $days = $start->diffInDays($end);
                $this->duration_time = round($days);
                break;
            default:
                $this->duration_time = '';
        }
    }

    public function fetchRecords()
    {
        $this->rentRecords = RentRecord::with([
            'tenant',
            'unit',
            'invoices' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])
        ->where('landlord_id', Auth::user()->landlord_id)
        ->latest()->get();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showFormModal = true;
        $this->isEdit = false;
        $this->generateInvoiceNumber();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteConfirmed()
    {
        RentRecord::destroy($this->deleteId);
        $this->dispatch('show-success-message', 'Record deleted successfully.');
        $this->showDeleteModal = false;
        $this->fetchRecords();
    }

    public function save()
    {
        $validationRules = [
            'tenant_id' => 'required',
            'unit_id' => 'required',
            'amount' => 'required',
            'vat' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date|after:start_date',
            'duration_time' => 'required',
            'agreement_document' => 'nullable|file|mimes:pdf,doc,docx|max:204800',
        ];


        if ($this->agreement_document && !is_string($this->agreement_document)) {
            $validationRules['agreement_document'] = 'required|file|mimes:pdf,doc,docx|max:20480';
        }

        $this->validate($validationRules);
        $documentUrl = null;
        if ($this->rentRecordId) {
            $record = RentRecord::find($this->rentRecordId);
            $documentUrl = $record ? $record->agreement_document : null;
        }
        if ($this->agreement_document && !is_string($this->agreement_document)) {
            $path = $this->agreement_document->store('agreements', 'public');
            $documentUrl = asset('storage/' . $path);
        }


        $rentRecord = RentRecord::updateOrCreate(
            ['id' => $this->rentRecordId],
            [
                'user_id' => Auth::id(),
                'landlord_id' => Auth::user()->landlord_id,
                'tenant_id' => $this->tenant_id,
                'unit_id' => $this->unit_id,
                'amount' => $this->amount,
                'vat' => $this->vat,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'duration_time' => $this->duration_time,
                'agreement_document' => $documentUrl,
            ],
        );

        $message = $this->rentRecordId ? 'Record updated successfully.' : 'Record created successfully.';
        $this->dispatch('show-success-message', message: $message);
        $this->resetForm();
        $this->fetchRecords();
        $this->showFormModal = false;
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

    public function confirmInvoice($id)
    {
        $this->invoiceRecordId = $id;
        $this->showInvoiceModal = true;
    }

    public function createInvoice()
    {
        // Validate using the rules
        $this->validate();

        // Get the rent record for reference
        $record = RentRecord::with(['tenant', 'unit'])->findOrFail($this->invoiceRecordId);

        $this->generateInvoiceNumber();

        $invoice = Invoice::create([
            'invoice_no' => $this->invoice_no,
            'tenant_id' => $this->tenant_id,
            'unit_id' => $this->unit_id,
            'rent_record_id' => $record->id,
            'amount' => $this->amount * $this->invoiced_months,
            'vat' => $this->vat * $this->invoiced_months,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'duration_units' => $this->invoiced_months,
            'invoice_status' => 'Pending',
            'landlord_id' => Auth::user()->landlord_id,
            'user_id' => Auth::id(),
            'property_id' => $this->property_id,
            'due_date' => $this->due_date,
            'email_sent' => false,
            'email_sent_count' => 0,
            'last_email_sent_at' => null,
            'status' => true
        ]);

        $this->dispatch('show-success-message', message: 'Invoice created successfully.');
        $this->showInvoiceModal = false;
        $this->closeModal(); // Close the modal after successful creation
    }

    public function viewInvoice($id)
    {
        $this->currentInvoice = Invoice::where('rent_record_id', $id)->first();
        if ($this->currentInvoice) {
            $this->showViewInvoiceModal = true;
        } else {
            $this->dispatch('show-error-message', message: 'Invoice not found.');
        }
    }

    public function closeViewInvoiceModal()
    {
        $this->showViewInvoiceModal = false;
        $this->currentInvoice = null;
    }

    public function hasInvoice($recordId)
    {
        return Invoice::where('rent_record_id', $recordId)->exists();
    }

    public function getInvoicedMonths($recordId)
    {
        return Invoice::where('rent_record_id', $recordId)
            ->where('invoice_status', '!=', 'Canceled') // Exclude canceled invoices
            ->sum('duration_units');
    }

    public function getRemainingMonths($recordId)
    {
        $record = RentRecord::find($recordId);
        $invoicedMonths = $this->getInvoicedMonths($recordId);
        return $record->duration_time - $invoicedMonths;
    }

    public function canCreateInvoice($recordId)
    {
        return $this->getRemainingMonths($recordId) > 0;
    }

    public function render()
    {
        return view('livewire.user.land-lord.rent-record.rent-record-livewire');
    }
}

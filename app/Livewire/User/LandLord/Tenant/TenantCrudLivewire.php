<?php

namespace App\Livewire\User\LandLord\Tenant;

use Livewire\Component;
use App\Models\Tenant;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TenantCrudLivewire extends Component
{
    use WithPagination;

    public $tenants;
    public $record_id;
    public $tenant_id;
    public $tenant_name;
    public $company_tin;
    public $company_name;
    public $notes;
    public $phone;
    public $email;
    public $isOpen = false;
    public $deleteModal = false;
    public $tenantToDelete;

    protected $rules = [
        'tenant_id' => 'required|string|size:16',
        'tenant_name' => 'required|string|max:255',
        'company_tin' => 'nullable|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'notes' => 'nullable|string',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
    ];

    public function mount()
    {
        $this->loadTenants();
    }

    public function loadTenants()
    {
        $this->tenants = Tenant::where('landlord_id',Auth::user()->landlord_id )->get();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
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
        $this->record_id = null;
        $this->tenant_id = '';
        $this->tenant_name = '';
        $this->company_tin = '';
        $this->company_name = '';
        $this->notes = '';
        $this->phone = '';
        $this->email = '';
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        if ($this->record_id) {
            $tenant = Tenant::find($this->record_id);
            if ($tenant) {
                $tenant->update([
                    'tenant_id' => $this->tenant_id,
                    'tenant_name' => $this->tenant_name,
                    'company_tin' => $this->company_tin,
                    'company_name' => $this->company_name,
                    'notes' => $this->notes,
                    'phone' => $this->phone,
                    'email' => $this->email,
                ]);
                $message = 'Tenant Updated Successfully.';
            }
        } else {
            Tenant::create([
                'user_id' => Auth::user()->id,
                'landlord_id'=>Auth::user()->landlord_id,
                'tenant_id' => $this->tenant_id,
                'tenant_name' => $this->tenant_name,
                'company_tin' => $this->company_tin,
                'company_name' => $this->company_name,
                'notes' => $this->notes,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);
            $message = 'Tenant Created Successfully.';
        }

        $this->dispatch('show-success-message', message: $message);
        $this->closeModal();
        $this->loadTenants();
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        $this->record_id = $tenant->id;
        $this->tenant_id = $tenant->tenant_id;
        $this->tenant_name = $tenant->tenant_name;
        $this->company_tin = $tenant->company_tin;
        $this->company_name = $tenant->company_name;
        $this->notes = $tenant->notes;
        $this->phone = $tenant->phone;
        $this->email = $tenant->email;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->tenantToDelete = $id;
        $this->deleteModal = true;
    }

    public function delete()
    {
        Tenant::find($this->tenantToDelete)->delete();
        $this->deleteModal = false;
        $this->loadTenants();
        $this->dispatch('show-success-message', message: 'Tenant Deleted Successfully.');
    }

    // Export all tenants to PDF
    public function exportAllToPdf()
    {
        $tenants = Tenant::where('user_id', Auth::id())->get();

        $pdf = PDF::loadView('livewire.user.land-lord.tenant.tenants-list-pdf', [
            'tenants' => $tenants,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'all-tenants.pdf');
    }

    public function render()
    {
        return view('livewire.user.land-lord.tenant.tenant-crud-livewire', [
            'tenants' => $this->tenants,
        ]);
    }
}

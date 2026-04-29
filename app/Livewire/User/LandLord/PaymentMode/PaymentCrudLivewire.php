<?php

namespace App\Livewire\User\LandLord\PaymentMode;

use App\Models\PaymentMode;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaymentCrudLivewire extends Component
{
    public $landlord_id;
    public $account_name;
    public $account_number;
    public $paymentModes = [];

    public $editingId = null;
    public $showModal = false;
    public $confirmingDeleteId = null;

    public function mount()
    {
        $this->landlord_id = Auth::user()->landlord_id;
        $this->loadPaymentModes();
    }

    public function loadPaymentModes()
    {
        $this->paymentModes = PaymentMode::with('paymentRecords')->where('landlord_id', $this->landlord_id)->get();
    }

    public function openCreateModal()
    {
        $this->reset(['account_name', 'account_number', 'editingId']);
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $payment = PaymentMode::findOrFail($id);
        $this->editingId = $payment->id;
        $this->account_name = $payment->account_name;
        $this->account_number = $payment->account_number;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'account_name' => 'required|string',
            'account_number' => 'required|string',
        ]);

        if ($this->editingId) {
            $payment = PaymentMode::findOrFail($this->editingId);
            $payment->update([
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
            ]);
        } else {
            PaymentMode::create([
                'landlord_id' => $this->landlord_id,
                'account_name' => $this->account_name,
                'account_number' => $this->account_number,
            ]);
        }

        $this->reset(['account_name', 'account_number', 'editingId', 'showModal']);
        $this->loadPaymentModes();
        $this->dispatch('show-success-message', message: 'Saved successfully!');
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete()
    {
        PaymentMode::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;
        $this->loadPaymentModes();
        $this->dispatch('show-success-message', message: 'Deleted successfully!');
    }

    public function render()
    {
        return view('livewire.user.land-lord.payment-mode.payment-crud-livewire');
    }
}

<?php

namespace App\Livewire\User\Admin;

use Livewire\Component;
use App\Models\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RequestLivewire extends Component
{
    public $requests;
    public $reply;
    public $status;
    public $selectedRequestId;
    public $landlord_id;
    public $showModal = false;

    public function mount()
    {
        $this->requests = UserRequest::latest()->get();
    }

    public function selectRequest($id)
    {
        $this->selectedRequestId = $id;
        $this->reply = '';
        $this->status = 'approved';
        $this->generateLandlordId();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['reply', 'status', 'selectedRequestId', 'landlord_id']);
        $this->showModal = false;
    }

    public function sendReply()
    {
        $request = UserRequest::find($this->selectedRequestId);

        if (!$request) {
            session()->flash('error', 'Request not found.');
            return;
        }

        $emailMessage = "Hello {$request->name},\n\n{$this->reply}\n\n";

        if ($this->status === 'approved') {
            $password = Str::random(8);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'company_email' => $request->email,
                'password' => Hash::make($password),
                'landlord_id' => $this->landlord_id,
                'property_id' => null,
                'userRole'=>'LANDLORD',
            ]);

            $emailMessage .= "\nYour request has been approved. You can log in using the following credentials:\n\n" .
                "Username: {$request->email}\n" .
                "Password: {$password}\n\n" .
                "Please change your password after logging in.";
        }

        Mail::raw($emailMessage, function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Response to Your Request');
        });

        $request->update([
            'status' => $this->status,
        ]);

        $this->dispatch('show-success-message', message: 'Reply sent and status updated successfully.');
        $this->closeModal();
        $this->requests = UserRequest::latest()->get();
    }

    public function generateLandlordId()
    {
        $prefix = 'LANDLORD-';
        $last = User::where('landlord_id', 'LIKE', $prefix . '%')->orderByDesc('id')->first();

        if ($last) {
            $lastNumber = (int) str_replace($prefix, '', $last->landlord_id);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $this->landlord_id = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.user.admin.request-livewire');
    }
}

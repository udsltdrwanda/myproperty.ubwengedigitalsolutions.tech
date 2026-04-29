<?php

namespace App\Livewire\User\LandLord\Manager;

use App\Models\User;
use App\Models\Property;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class PropertManagersCrudLivewire extends Component
{
    public $managers, $managerId;
    public $name, $email, $password, $password_confirmation, $terms, $property_id;
    public $isEditing = false;
    public $isDeleteModalOpen = false;
    public $isFormModalOpen = false;
    public $properties = [];

    public function mount()
    {
        $this->fetchManagers();
        $this->fetchProperties();
    }

    public function fetchManagers()
    {
        $this->managers = User::where('landlord_id', Auth::user()->landlord_id)
            ->where('id', '!=', Auth::id())
            ->get();
    }

    public function fetchProperties()
    {
        $this->properties = Auth::user()->properties;
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'terms', 'managerId', 'isEditing', 'property_id']);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->isFormModalOpen = true;
    }

    public function openEditModal($id)
    {
        $manager = User::findOrFail($id);
        $this->managerId = $manager->id;
        $this->name = $manager->name;
        $this->email = $manager->email;
        $this->property_id = $manager->property_id;
        $this->isEditing = true;
        $this->isFormModalOpen = true;
    }

    public function openDeleteModal($id)
    {
        $this->managerId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => config('jetstream.has_terms_and_privacy_policy_feature') ? 'accepted' : '',
        ]);

        $generatedPassword = $this->password;



        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'userRole' => UserRole::EMPLOYEE->value,
            'password' => Hash::make($generatedPassword),
            'landlord_id' => Auth::user()->landlord_id,
        ]);

        $emailMessage = <<<EOT
        🎉 Welcome to the Team, {$this->name}! 🎉

        You have been registered as a Property Manager under MBG Management System.

        Here are your login credentials:
        🔑 Username: {$this->email}
        🔐 Password: {$generatedPassword}

        Please make sure to change your password after logging in for your account's security.

        We're excited to have you onboard and look forward to great work together!

        Warm regards,
        MBG
        EOT;

        Mail::raw($emailMessage, function ($message) {
            $message->to($this->email)->subject('🎉 Welcome to MBG Property Management');
        });

        $this->dispatch('show-success-message', message: 'Manager created and email sent successfully.');
        $this->resetForm();
        $this->isFormModalOpen = false;
        $this->fetchManagers();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$this->managerId}",
            'property_id' => ['required', 'exists:properties,id'],
        ]);

        $manager = User::findOrFail($this->managerId);
        $manager->update([
            'name' => $this->name,
            'email' => $this->email,
            'property_id' => $this->property_id,
        ]);

        $this->dispatch('show-success-message', message: 'Manager updated successfully.');
        $this->resetForm();
        $this->isFormModalOpen = false;
        $this->fetchManagers();
    }

    public function delete()
    {
        $user = User::findOrFail($this->managerId);
        $user->update([
            'landlord_id' => null,
        ]);
        $this->dispatch('show-success-message', message: 'Manager removed from your list.');
        $this->isDeleteModalOpen = false;
        $this->fetchManagers();
    }

    public function render()
    {
        return view('livewire.user.land-lord.manager.propert-managers-crud-livewire');
    }
}

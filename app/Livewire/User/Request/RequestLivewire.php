<?php

namespace App\Livewire\User\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Models\UserRequest;
use Illuminate\Database\QueryException;

class RequestLivewire extends Component
{
    public $name;
    public $email;
    public $phone;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
    ];

    public function submit()
    {
        $this->validate();

        try {
            UserRequest::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);
            return redirect()->route('home')->with('success', 'Your request has been submitted successfully.');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->route('home')->with('error', 'This email has already been used. Please use a different email.');
            } else {
                return redirect()->route('home')->with('error', 'An error occurred while submitting your request. Please try again later.');
            }
        }
    }

    public function render()
    {
        return view('livewire.user.request.request-livewire');
    }
}

<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Automatically generate a unique landlord_id
        $prefix = 'LANDLORD-';
        $last = User::where('landlord_id', 'LIKE', $prefix . '%')->orderByDesc('id')->first();

        if ($last) {
            $lastNumber = (int) str_replace($prefix, '', $last->landlord_id);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $landlordId = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'company_email' => $input['email'],
            'password' => Hash::make($input['password']),
            'landlord_id' => $landlordId,
            'property_id' => null,
            'userRole' => UserRole::LANDLORD->value,
        ]);
    }
}

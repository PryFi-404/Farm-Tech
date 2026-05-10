<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admin and officer can add farmers
        return auth()->user()->isAdmin() || auth()->user()->isOfficer();
    }

    public function rules(): array
    {
        return [
            // User account fields
            'name'         => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8'],

            // Farmer profile fields
            'aadhaar'      => ['required', 'digits:12', 'unique:farmers,aadhaar'],
            'voter_id'     => ['nullable', 'string', 'max:20', 'unique:farmers,voter_id'],
            'phone'        => ['required', 'digits_between:10,10'],
            'dob'          => ['nullable', 'date', 'before:today'],
            'gender'       => ['required', 'in:Male,Female,Other'],
            'address'      => ['required', 'string', 'max:255'],
            'village'      => ['required', 'string', 'max:100'],
            'block'        => ['required', 'string', 'max:100'],
            'district'     => ['required', 'string', 'max:100'],
            'state'        => ['required', 'string', 'max:100'],
            'pincode'      => ['required', 'digits:6'],
            'photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Bank details
            'bank_account' => ['nullable', 'string', 'max:20'],
            'bank_name'    => ['nullable', 'string', 'max:100'],
            'ifsc'         => ['nullable', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'aadhaar.digits'    => 'Aadhaar number must be exactly 12 digits.',
            'phone.digits_between' => 'Phone number must be 10 digits.',
            'ifsc.regex'        => 'IFSC code format is invalid (e.g. SBIN0001234).',
            'pincode.digits'    => 'Pincode must be exactly 6 digits.',
            'photo.max'         => 'Photo must be less than 2MB.',
        ];
    }
}

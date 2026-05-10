<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isOfficer();
    }

    public function rules(): array
    {
        $farmerId = $this->route('farmer')->id ?? null;

        return [
            'name'         => ['required', 'string', 'max:100'],
            'phone'        => ['required', 'digits_between:10,10'],
            'aadhaar'      => ['required', 'digits:12', "unique:farmers,aadhaar,{$farmerId}"],
            'voter_id'     => ['nullable', 'string', 'max:20', "unique:farmers,voter_id,{$farmerId}"],
            'dob'          => ['nullable', 'date', 'before:today'],
            'gender'       => ['required', 'in:Male,Female,Other'],
            'address'      => ['required', 'string', 'max:255'],
            'village'      => ['required', 'string', 'max:100'],
            'block'        => ['required', 'string', 'max:100'],
            'district'     => ['required', 'string', 'max:100'],
            'state'        => ['required', 'string', 'max:100'],
            'pincode'      => ['required', 'digits:6'],
            'photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'bank_account' => ['nullable', 'string', 'max:20'],
            'bank_name'    => ['nullable', 'string', 'max:100'],
            'ifsc'         => ['nullable', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
        ];
    }
}

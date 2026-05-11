<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShgRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isOfficer();
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:150'],
            'type'                => ['required', 'in:SHG,FPG,FPC,JLG'],
            'registration_number' => ['nullable', 'string', 'max:60', 'unique:shgs,registration_number,' . ($this->route('shg')?->id ?? 'NULL')],
            'formation_date'      => ['nullable', 'date', 'before_or_equal:today'],
            'village'             => ['required', 'string', 'max:100'],
            'block'               => ['required', 'string', 'max:100'],
            'district'            => ['required', 'string', 'max:100'],
            'leader_farmer_id'    => ['nullable', 'exists:farmers,id'],
            'total_members'       => ['nullable', 'integer', 'min:1'],
            'bank_account'        => ['nullable', 'string', 'max:25'],
        ];
    }
}

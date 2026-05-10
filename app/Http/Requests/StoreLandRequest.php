<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isOfficer();
    }

    public function rules(): array
    {
        return [
            'farmer_id'      => ['required', 'exists:farmers,id'],
            'survey_number'  => ['required', 'string', 'max:50'],
            'area_acres'     => ['required', 'numeric', 'min:0.01', 'max:999'],
            'soil_type'      => ['nullable', 'string', 'max:50'],
            'irrigation_type'=> ['nullable', 'string', 'max:50'],
            'ownership_type' => ['required', 'in:Owned,Leased,Shared'],
            'khasra_number'  => ['nullable', 'string', 'max:50'],
            'document'       => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'],
        ];
    }
}

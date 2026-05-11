<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Farmers apply themselves; admins/officers apply on behalf
        return true;
    }

    public function rules(): array
    {
        return [
            'farmer_id'  => ['required', 'exists:farmers,id'],
            'scheme_id'  => ['required', 'exists:schemes,id'],
            'remarks'    => ['nullable', 'string', 'max:1000'],
            'applied_date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'farmer_id.required' => 'Please select a farmer.',
            'scheme_id.required' => 'Please select a scheme.',
        ];
    }
}

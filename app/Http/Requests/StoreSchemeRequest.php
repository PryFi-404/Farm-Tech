<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:200'],
            'description'     => ['required', 'string'],
            'eligibility'     => ['nullable', 'string'],
            'benefit_amount'  => ['nullable', 'numeric', 'min:0'],
            'max_beneficiaries' => ['nullable', 'integer', 'min:1'],
            'start_date'      => ['nullable', 'date'],
            'end_date'        => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'       => ['boolean'],
            'category'        => ['required', 'in:Subsidy,Insurance,Loan,Training,Equipment,Other'],
            'ministry'        => ['nullable', 'string', 'max:200'],
        ];
    }
}

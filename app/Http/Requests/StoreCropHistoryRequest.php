<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCropHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isOfficer();
    }

    public function rules(): array
    {
        return [
            'farmer_id'    => ['required', 'exists:farmers,id'],
            'land_id'      => ['required', 'exists:lands,id'],
            'crop_id'      => ['required', 'exists:crops,id'],
            'season'       => ['required', 'in:Kharif,Rabi,Zaid,Year Round'],
            'year'         => ['required', 'digits:4', 'min:2000', 'max:' . (date('Y') + 1)],
            'area_used'    => ['required', 'numeric', 'min:0.01'],
            'production_kg'=> ['nullable', 'numeric', 'min:0'],
            'selling_price'=> ['nullable', 'numeric', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ];
    }
}

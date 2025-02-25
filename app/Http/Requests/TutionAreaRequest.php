<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutionAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users can create/update their tution area.
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'lat'         => 'required|numeric',
            'long'        => 'required|numeric',
            // The following fields are optional:
            'address'     => 'nullable|string',
            'division_id' => 'nullable|integer|exists:divisions,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'upazila_id'  => 'nullable|integer|exists:upazilas,id',
            'union_id'    => 'nullable|integer|exists:unions,id',
            'status'      => 'nullable|boolean',
        ];
    }
}

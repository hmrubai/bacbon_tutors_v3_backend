<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust authorization logic if needed.
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en'   => 'required|string|max:255',
            'name_bn'   => 'nullable|string|max:255',
            'remarks'   => 'nullable|string',
            'medium_id' => 'required|integer', // Optionally: |exists:mediums,id
            'grade_id'  => 'required|integer',   // Optionally: |exists:grades,id
        ];
    }
}

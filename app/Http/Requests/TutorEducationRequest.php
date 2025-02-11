<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer',
            'title' => 'required|string',
            'institute' => 'required|string',
            'discipline' => 'nullable|string',
            'passing_year' => 'nullable|string',
            'sequence' => 'integer',
            'created_by' => 'nullable|integer',
            'is_active' => 'boolean'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectExpertiseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all for now; adjust as needed.
        return true;
    }

    public function rules(): array
    {
        return [
            'medium_id'  => 'required|integer',
            'grade_id'   => 'required|integer',
            'subject_id' => 'required|integer',
            // For tutors, user_id will be auto-populated so it can be nullable here.
            'user_id'    => 'nullable|integer',
            'remarks'    => 'nullable|string',
            'status'     => 'nullable|boolean',
        ];
    }
}

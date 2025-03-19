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
            'grade_id'   => 'required|array',
            'subject_id' => 'required|integer',
            // For tutors, user_id will be auto-populated so it can be nullable here.
            'user_id'    => 'nullable|integer',
            'remarks'    => 'nullable|string',
            'status'     => 'nullable|boolean',
            'tuition_type' => 'nullable|in:offline,online,both',
            'rate'         => 'nullable|in:hourly,fixed,monthly,yearly',
            'fee'          => 'nullable|numeric|min:0',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorWorkExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all for now; adjust as needed.
        return true;
    }

    public function rules(): array
    {
        return [
            // For tutors, user_id might be auto-populated.
            // 'user_id'         => 'nullable|integer',
            'employment_type' => 'required|integer',  // Must be provided (employee type id)
            'designation'     => 'required|string',
            'company_name'    => 'required|string',
            'currently_working'=> 'required|boolean',
            'start_date'      => 'required',
            'end_date'        => 'nullable|date',
        ];
    }
}

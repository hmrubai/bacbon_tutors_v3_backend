<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow only authenticated users.
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'student_id'    => 'nullable|integer',
            'kid_id'        => 'nullable|integer',
            'job_title'     => 'required|string|max:255',
            'slot_days'     => 'required|integer',
            'slot_type'     => 'required|in:Month,Week',
            'salary_amount' => 'required|numeric',
            'gender'        => 'required|in:Male,Female,Others',
            'salary_type'   => 'required|in:Hour,Week,Month',
            // Validate tutoring_time as a time format (e.g., "14:30:00")
            'tutoring_time' => 'required|date_format:H:i:s',
            'medium_id'     => 'required|integer|exists:mediums,id',
            'subject_ids'    => 'required|array',
            'note'          => 'nullable|string',
            'tuition_type'  => 'required|in:Online,Offline',
            'grade_id'      => 'nullable|integer',
            'division_id'   => 'nullable|integer',
            'district_id'   => 'nullable|integer',
            'upazila_id'    => 'nullable|integer',
            'area_id'       => 'nullable|integer',
            'institute_ids' => 'nullable|array',
            'negotiable'    => 'required|boolean',
            
        ];
    }
}

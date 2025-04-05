<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_id' => 'required|exists:tutor_jobs,id',
            // 'tutor_id' => 'required|exists:users,id',
            'cover_letter' => 'nullable|string|max:3000',
            'expected_salary' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'job_id.required' => 'The job ID is required.',
            'job_id.exists' => 'The selected job does not exist.',
            // 'tutor_id.required' => 'The Tutor ID is required.',
            'expected_salary.numeric' => 'Expected salary must be a number.',
            'expected_salary.min' => 'Expected salary must be at least 0.',
        ];
    }
}

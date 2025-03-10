<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KidInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow authenticated users (or tutors) to update their kid info.
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'age'      => 'nullable|integer',
            'gender'   => 'nullable|in:Male,Female,Others',
            'class_id' => 'required|integer|exists:grade,id', // Make sure it exists in the Grade table (the table used in your Grade CRUD)
            'institute'=> 'nullable|string|max:255',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only tutors should be allowed, but we'll enforce that in the controller.
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'designation'  => 'nullable|string|max:255',
            'organization' => 'required|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            // user_id is auto-populated, so we don't validate it here.
        ];
    }
}

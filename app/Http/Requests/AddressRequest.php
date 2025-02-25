<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all authenticated users (tutors) for this example
        return true;
    }

    public function rules(): array
    {
        return [
            'present_address'   => 'required|string',
            'permanent_address' => 'nullable|string',
            // user_id is auto-populated from Auth
        ];
    }
}

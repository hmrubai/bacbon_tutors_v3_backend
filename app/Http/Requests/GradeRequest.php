<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust the authorization logic as needed.
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en'   => 'required|string|max:255',
            'name_bn'   => 'nullable|string|max:255',
            'remarks'   => 'nullable|string',
            'medium_id' => 'required|integer', // You may add exists:mediums,id if you want to enforce a valid Medium ID.
        ];
    }
}

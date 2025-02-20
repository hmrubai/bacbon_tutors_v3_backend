<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediumRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Adjust authorization logic as needed.
        return true;
    }

    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'remarks'  => 'nullable|string',
        ];
    }
}

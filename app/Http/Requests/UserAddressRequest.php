<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'present_division_id' => ['nullable'],
            'present_district_id' => ['nullable'],
            'present_area_id' => ['nullable'],
            'present_address' => ['nullable', 'string', 'max:255'],
            'permanent_division_id' => ['nullable'],
            'permanent_district_id' => ['nullable'],
            'present_upazila_id' => ['nullable'],
            'permanent_upazila_id' => ['nullable'],
            'permanent_area_id' => ['nullable'],
            'permanent_address' => ['nullable', 'string', 'max:255'],
        ];
    }
}

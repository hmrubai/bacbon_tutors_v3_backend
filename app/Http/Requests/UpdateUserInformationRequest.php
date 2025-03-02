<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow if the user is authenticated
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'profile_image'    => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_number'   => 'nullable|string|max:50',
            'alternate_number' => 'nullable|string|max:50',
            'email'            => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'date_of_birth'    => 'nullable|date',
            'religion'         => 'nullable|string|max:255',
            'fathers_name'     => 'nullable|string|max:255',
            'mothers_name'     => 'nullable|string|max:255',
            'gender'           => 'required|in:Male,Female,Other',
            'blood_group'      => 'nullable|string|max:10',
            'bio'              => 'nullable|string',
        ];
    }
}

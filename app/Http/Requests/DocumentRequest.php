<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow authenticated users to upload documents.
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'document_image' => 'required|file|max:10240',
            'document_type'  => 'required|in:certificate,national_id,passport,birth_certificate',
            // When user uploads, approval and approved_by are not provided.
            'approval'       => 'nullable|boolean',
            'approved_by'    => 'nullable|integer',
            'status'         => 'nullable|boolean',
        ];
    }
}

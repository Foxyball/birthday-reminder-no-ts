<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportContactsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => __('messages.import_file_required'),
            'file.file' => __('messages.import_file_invalid'),
            'file.mimes' => __('messages.import_file_format'),
            'file.max' => __('messages.import_file_too_large'),
        ];
    }
}

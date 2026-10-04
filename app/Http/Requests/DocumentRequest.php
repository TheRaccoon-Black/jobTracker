<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,png,jpg,jpeg,zip', 'max:5120'],
            'type' => ['required', Rule::in(\App\Models\Document::TYPES)],
            'application_id' => ['nullable', 'exists:applications,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Format file tidak didukung. Gunakan PDF, DOC/DOCX, PPT, XLS, TXT, PNG/JPG, atau ZIP.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(\App\Models\Application::STATUSES)],
            'job_posting_link' => ['nullable', 'url', 'max:2048'],
            'contact' => ['nullable', 'string', 'max:255'],
            'application_date' => ['nullable', 'date'],
            'salary_range' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'next_follow_up' => ['nullable', 'date'],
            'follow_up_notes' => ['nullable', 'string', 'max:2000'],
            'tags' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'company.required' => 'Nama perusahaan wajib diisi.',
            'title.required' => 'Nama posisi wajib diisi.',
            'job_posting_link.url' => 'Tautan lowongan harus berupa URL valid.',
        ];
    }
}

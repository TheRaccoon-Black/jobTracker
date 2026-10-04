<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'interview_stage' => ['required', Rule::in(\App\Models\Interview::STAGES)],
            'interviewer' => ['nullable', 'string', 'max:255'],
            'schedule_date' => ['nullable', 'date'],
            'link_meeting' => ['nullable', 'url', 'max:2048'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'interviewer.max' => 'Nama pewawancara maksimal 255 karakter.',
        ];
    }
}

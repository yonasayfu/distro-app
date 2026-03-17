<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HandbookIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'document' => ['nullable', 'string', 'max:100'],
            'lesson' => ['nullable', 'string', 'max:150'],
        ];
    }
}

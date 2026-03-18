<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PreviewPageImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('pages.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimetypes:text/plain,text/csv,text/tsv,application/vnd.ms-excel', 'max:2048'],
        ];
    }
}

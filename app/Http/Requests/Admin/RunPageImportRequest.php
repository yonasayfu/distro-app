<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RunPageImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('pages.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'import_run_id' => ['required', 'integer', 'exists:import_runs,id'],
        ];
    }
}

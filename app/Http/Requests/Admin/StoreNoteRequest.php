<?php

namespace App\Http\Requests\Admin;

use App\Support\NoteableRegistry;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('notes.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'noteable_type' => ['required', 'string', Rule::in(NoteableRegistry::aliases())],
            'noteable_id' => ['required', 'integer', 'min:1'],
            'content' => ['required', 'string', 'min:3', 'max:5000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'noteable_type.required' => 'Select the record this note belongs to.',
            'noteable_type.in' => 'The selected note target is not supported by the shared notes module.',
            'noteable_id.required' => 'The target record identifier is required.',
            'noteable_id.integer' => 'The target record identifier must be numeric.',
            'content.required' => 'Write the note content before saving.',
            'content.min' => 'Notes should be at least 3 characters long.',
            'content.max' => 'Notes may not be longer than 5000 characters.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $type = $this->input('noteable_type');
            $id = $this->input('noteable_id');

            if (! is_string($type) || ! is_numeric($id)) {
                return;
            }

            if (NoteableRegistry::resolve($type, (int) $id) === null) {
                $validator->errors()->add('noteable_id', 'The selected record could not be found.');
            }
        });
    }
}

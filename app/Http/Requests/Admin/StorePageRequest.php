<?php

namespace App\Http\Requests\Admin;

use App\PageStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('pages.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $title = (string) $this->input('title', '');
        $slug = (string) $this->input('slug', '');

        $this->merge([
            'slug' => Str::slug($slug !== '' ? $slug : $title),
            'status' => (string) $this->input('status', PageStatus::Draft->value),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('pages', 'slug'),
                Rule::notIn($this->reservedSlugs()),
            ],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::enum(PageStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.not_in' => 'That slug is reserved by the boilerplate and cannot be used for a public page.',
        ];
    }

    /**
     * @return list<string>
     */
    private function reservedSlugs(): array
    {
        return [
            'admin',
            'api',
            'dashboard',
            'exports',
            'handbook',
            'login',
            'notifications',
            'password',
            'register',
            'search',
            'settings',
            'up',
        ];
    }
}

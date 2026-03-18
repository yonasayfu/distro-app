<?php

namespace App\Http\Requests\Admin;

use App\Models\Page;
use App\PageStatus;
use App\Support\WorkflowTransitionRegistry;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('pages.update') ?? false;
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
        /** @var Page $page */
        $page = $this->route('page');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('pages', 'slug')->ignore($page->id),
                Rule::notIn($this->reservedSlugs()),
            ],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::enum(PageStatus::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var Page $page */
            $page = $this->route('page');

            $targetStatus = (string) $this->input('status');

            if ($targetStatus === '' || $page->status->value === $targetStatus) {
                return;
            }

            if (! WorkflowTransitionRegistry::canTransitionPage($page->status->value, $targetStatus)) {
                $validator->errors()->add('status', 'That page status transition is not allowed by the shared workflow rules.');
            }
        });
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

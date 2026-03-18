<?php

namespace App\Http\Requests\Admin;

use App\Support\SettingRegistry;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('settings.update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        foreach (SettingRegistry::fields() as $key => $field) {
            $rules[$key] = match ($field['type']) {
                'email' => ['nullable', 'email:rfc', 'max:255'],
                'textarea' => ['nullable', 'string', 'max:5000'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        return $rules;
    }

    /**
     * @return array<string, string|null>
     */
    public function validated($key = null, $default = null): array
    {
        /** @var array<string, string|null> $validated */
        $validated = parent::validated();

        return collect(SettingRegistry::fields())
            ->keys()
            ->mapWithKeys(fn (string $settingKey): array => [
                $settingKey => ($validated[$settingKey] ?? null) !== '' ? ($validated[$settingKey] ?? null) : null,
            ])
            ->all();
    }
}

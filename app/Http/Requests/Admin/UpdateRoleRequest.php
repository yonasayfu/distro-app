<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('roles.update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permissions' => ['present', 'array'],
            'permissions.*' => ['string', 'distinct', 'exists:permissions,name'],
        ];
    }

    /**
     * Get the custom messages for the validator.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'permissions.present' => 'The permissions payload must always be present, even when no permissions are selected.',
            'permissions.array' => 'The permissions payload must be sent as an array of permission names.',
            'permissions.*.distinct' => 'Each permission may only be selected once.',
            'permissions.*.exists' => 'One of the selected permissions does not exist in the system.',
        ];
    }
}

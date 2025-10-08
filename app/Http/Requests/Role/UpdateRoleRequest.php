<?php

namespace App\Http\Requests\Role;

use App\Enums\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('update_roles');
    }

    public function rules(): array
    {
        $roleId = $this->route('roleId');
        
        return [
            'name' => 'sometimes|string|max:255|unique:roles,name,' . $roleId,
            'permissions' => 'sometimes|array',
            'permissions.*' => ['string', Rule::in(PermissionEnum::getAllPermissions())],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A role with this name already exists.',
            'permissions.array' => 'Permissions must be an array.',
            'permissions.*.in' => 'Invalid permission provided.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            validation_failed('Role validation failed', $validator->errors()->toArray())
        );
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            forbidden('You do not have permission to update roles')
        );
    }
}
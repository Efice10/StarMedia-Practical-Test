<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage_system');
    }

    public function rules(): array
    {
        $permissionId = $this->route('permissionId');
        
        return [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permissionId,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Permission name is required.',
            'name.unique' => 'A permission with this name already exists.',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            validation_failed('Permission validation failed', $validator->errors()->toArray())
        );
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            forbidden('You do not have permission to update permissions')
        );
    }
}
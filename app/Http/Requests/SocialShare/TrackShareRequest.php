<?php

namespace App\Http\Requests\SocialShare;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TrackShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'url' => 'required|url|max:2048',
            'page_title' => 'nullable|string|max:500',
            'social_platform_id' => 'required|exists:social_platforms,id',
            'metadata' => 'nullable|array',
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'url.required' => 'URL is required',
            'url.url' => 'Please provide a valid URL',
            'social_platform_id.required' => 'Social platform is required',
            'social_platform_id.exists' => 'Invalid social platform selected',
        ];
    }

    /**
     * Handle a failed validation attempt
     */
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            validation_failed('Validation failed', $validator->errors()->toArray())
        );
    }
}

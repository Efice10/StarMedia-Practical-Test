<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetAnalyticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('view_analytics');
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_id' => 'nullable|exists:social_platforms,id',
            'group_by' => 'nullable|in:platform,date',
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'End date must be after or equal to start date',
            'platform_id.exists' => 'Invalid platform selected',
            'group_by.in' => 'Group by must be either platform or date',
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

    /**
     * Handle a failed authorization attempt
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            forbidden('You do not have permission to view analytics')
        );
    }
}

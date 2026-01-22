<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreEventRequest
 *
 * Validates incoming event creation requests
 * Ensures all required fields are present and meet business rules
 */
class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool True if authenticated user can create events
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }


    /**
     * Get the validation rules that apply to the request
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:2000',
            ],
            'location' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'start_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:now',
            ],
            'end_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:start_time',
            ],
            'capacity' => [
                'required',
                'integer',
                'min:1',
                'max:10000',
            ],
            'price' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required',
            'title.min' => 'Event title must be at least 3 characters',
            'title.max' => 'Event title cannot exceed 255 characters',
            'description.required' => 'Event description is required',
            'description.min' => 'Event description must be at least 10 characters',
            'description.max' => 'Event description cannot exceed 2000 characters',
            'location.required' => 'Event location is required',
            'location.min' => 'Event location must be at least 3 characters',
            'location.max' => 'Event location cannot exceed 255 characters',
            'start_time.required' => 'Event start time is required',
            'start_time.date_format' => 'Start time must be in format: Y-m-d H:i:s',
            'start_time.after' => 'Event start time must be in the future',
            'end_time.required' => 'Event end time is required',
            'end_time.date_format' => 'End time must be in format: Y-m-d H:i:s',
            'end_time.after' => 'Event end time must be after start time',
            'capacity.required' => 'Event capacity is required',
            'capacity.integer' => 'Event capacity must be a whole number',
            'capacity.min' => 'Event capacity must be at least 1',
            'capacity.max' => 'Event capacity cannot exceed 10000',
            'price.numeric' => 'Event price must be a valid number',
            'price.min' => 'Event price cannot be negative',
            'price.max' => 'Event price cannot exceed 999999.99',
        ];
    }

    /**
     * Get the validated data from the request
     * Applies any data transformations before validation
     *
     * @return array Validated and transformed event data
     */

    public function validated($key = null, $default = null)
{
    $data = parent::validated($key, $default);

    // Map capacity to venue_capacity
    if (isset($data['capacity'])) {
        $data['venue_capacity'] = $data['capacity'];
        unset($data['capacity']);
    }

    return $data;
}




}

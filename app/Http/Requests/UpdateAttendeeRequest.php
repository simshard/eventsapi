<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateEventRequest
 *
 * Validates partial event updates
 * All fields are optional to allow partial updates
 */
class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'location' => 'sometimes|string|max:255',
            'start_time' => 'sometimes|date_format:Y-m-d H:i:s|before:end_time',
            'end_time' => 'sometimes|date_format:Y-m-d H:i:s|after:start_time',
            'venue_capacity' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0|nullable',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.string' => 'Event title must be a string.',
            'title.max' => 'Event title cannot exceed 255 characters.',
            'description.string' => 'Event description must be a string.',
            'description.max' => 'Event description cannot exceed 1000 characters.',
            'location.string' => 'Event location must be a string.',
            'location.max' => 'Event location cannot exceed 255 characters.',
            'start_time.date_format' => 'Event start time must be in format: Y-m-d H:i:s',
            'start_time.before' => 'Event start time must be before end time.',
            'end_time.date_format' => 'Event end time must be in format: Y-m-d H:i:s',
            'end_time.after' => 'Event end time must be after start time.',
            'venue_capacity.integer' => 'Event capacity must be an integer.',
            'venue_capacity.min' => 'Event capacity must be at least 1.',
            'price.numeric' => 'Event price must be a number.',
            'price.min' => 'Event price cannot be negative.',
        ];
    }
}

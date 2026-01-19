<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // User must be authenticated via middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'event_id' => [
                'required',
                'integer',
                'exists:events,id', // Event must exist
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'event_id.required' => 'Event ID is required',
            'event_id.exists' => 'The selected event does not exist',
            'event_id.integer' => 'Event ID must be an integer',
        ];
    }
}

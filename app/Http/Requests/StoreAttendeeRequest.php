<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:attendees,email',
            'phone' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Event ID is required',
            'event_id.exists' => 'The selected event does not exist',
            'name.required' => 'Attendee name is required',
            'name.string' => 'Attendee name must be a string',
            'name.max' => 'Attendee name cannot exceed 255 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'This email is already registered for this event',
            'phone.required' => 'Phone number is required',
            'phone.string' => 'Phone number must be a string',
            'phone.max' => 'Phone number cannot exceed 20 characters',
        ];
    }
}

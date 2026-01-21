<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:attendees,email,' . $this->attendee->id,
            'phone' => 'sometimes|string|max:20',
            'status' => 'sometimes|in:confirmed,cancelled',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location' => 'nullable|string',
        'venue_name' => 'nullable|string',
        'fee' => 'nullable|numeric',
        'currency' => 'nullable|string',
        'venue_capacity' => 'required|integer|min:1',
        'start_time' => 'required|date_format:Y-m-d H:i:s',
        'end_time' => 'required|date_format:Y-m-d H:i:s',
    ];
    }
}

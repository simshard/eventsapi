<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * EventResource
 *
 * Serializes Event model for API responses
 * Ensures consistent data structure across all endpoints
 */
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'start_time' => $this->start_time?->toIso8601String(),
            'end_time' => $this->end_time?->toIso8601String(),
            'venue_capacity' => $this->venue_capacity,
            'available_capacity' => $this->available_capacity,
            'is_fully_booked' => $this->is_fully_booked,
            'price' => $this->price,
            'owner' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'booking_count' => $this->bookings()->where('status', 'confirmed')->count(),
            'booking_percentage' => $this->getBookingPercentage(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

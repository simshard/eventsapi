<?php

namespace App\Services;

use App\Models\Event;
use Exception;

class EventAvailabilityService
{
    public static function checkCapacity(Event $event): void
    {
        $booked = $event->bookings()->count();
        if ($booked >= $event->venue_capacity) {
            throw new Exception('Event is fully booked');
        }
    }

    public static function getAvailability(Event $event): array
    {
        $booked = $event->bookings()->count();
        $available = $event->venue_capacity - $booked;

        return [
            'total_capacity' => $event->venue_capacity,
            'booked' => $booked,
            'available' => $available,
            'is_full' => $available <= 0,
        ];
    }
}

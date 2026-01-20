<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use Exception;

class BookingService
{
    public function __construct(private BookingRepository $repository) {}

    public function bookEvent(int $userId, int $eventId): Booking
    {
         $event = Event::findOrFail($eventId);

        // Check capacity
        if ($event->bookings()->count() >= $event->venue_capacity) {
            throw new Exception('Event is fully booked');
        }

        // Check duplicate booking
        if ($event->bookings()->where('user_id', $userId)->exists()) {
            throw new Exception('User already booked this event');
        }

        return $this->repository->create([
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);
    }

    public function cancelBooking(int $bookingId): void
    {
        $this->repository->delete($bookingId);
    }
}

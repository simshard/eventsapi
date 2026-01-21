<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Services\EventAvailabilityService;
use Exception;

class BookingService
{
    public function __construct(private BookingRepository $repository) {}


    /**
     * Create a booking for a user on an event
     *
     * @param int $userId
     * @param int $eventId
     * @return Booking
     * @throws Exception if event is fully booked or user already booked
     */
    public function bookEvent(int $userId, int $eventId): Booking
    {
        $event = Event::findOrFail($eventId);

        EventAvailabilityService::checkCapacity($event);

        if ($this->userAlreadyBooked($userId, $eventId)) {
            throw new Exception('User already booked this event');
        }
        return $this->repository->create([
            'user_id' => $userId,
            'event_id' => $eventId,
        ]);
    }

    /**
     * Get all bookings for a user
     *
     * @param int $userId
     * @return array
     */
    public function getUserBookings(int $userId): array
    {
        return $this->repository->getByUser($userId);
    }

    /**
     * Get all bookings for an event
     *
     * @param int $eventId
     * @return array
     */
    public function getEventBookings(int $eventId): array
    {
        return $this->repository->getByEvent($eventId);
    }

    /**
     * Get booking count for an event
     *
     * @param int $eventId
     * @return int
     */
    public function getBookingCount(int $eventId): int
    {
        return $this->repository->countByEvent($eventId);
    }

    /**
     * Check if user has already booked an event
     *
     * @param int $userId
     * @param int $eventId
     * @return bool
     */
    private function userAlreadyBooked(int $userId, int $eventId): bool
    {
        return $this->repository->userHasBooked($userId, $eventId);
    }

    /**
     * Cancel a booking
     *
     * @param int $bookingId
     * @return void
     * @throws Exception if booking not found
     */
    public function cancelBooking(int $bookingId): void
    {
        $booking = $this->repository->findById($bookingId);
        if (!$booking) {
        throw new Exception('Booking not found');
      }
        $this->repository->delete($bookingId);
    }


}

<?php

namespace App\Services;

use App\Models\Booking;

interface BookingServiceInterface
{
    public function bookEvent(int $userId, int $eventId): Booking;

    public function getUserBookings(int $userId): array;

    public function getEventBookings(int $eventId): array;

    public function getBookingCount(int $eventId): int;

    public function cancelBooking(int $bookingId): void;
}

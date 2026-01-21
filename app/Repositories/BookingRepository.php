<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class BookingRepository
{
    /**
     * Create a new booking
     */
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    /**
     * Get booking by ID
     */
    public function findById(int $id): Booking
    {
        return Booking::findOrFail($id);
    }

    /**
     * Update a booking
     */
    public function update(int $id, array $data): Booking
    {
        $booking = Booking::findOrFail($id);
        $booking->update($data);
        return $booking;
    }

    /**
     * Delete a booking
     */
    public function delete(int $id): void
    {
        Booking::destroy($id);
    }

    /**
     * Get all bookings for an event
     */
    public function getByEvent(int $eventId): Collection
    {
        return Booking::where('event_id', $eventId)->get();
    }

    /**
     * Get all bookings for a user
     */
    public function getByUser(int $userId): Collection
    {
        return Booking::where('user_id', $userId)->get();
    }

    /**
     * Count bookings for a user
     */
     public function countByUser(int $userId): int
     {
         return Booking::where('user_id', $userId)->count();
     }

    /**
     * Count bookings for an event
     */
    public function countByEvent(int $eventId): int
    {
        return Booking::where('event_id', $eventId)->count();
    }

    /**
     * Check if user already booked event
     */
    public function userHasBooked(int $userId, int $eventId): bool
    {
        return Booking::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    }

    /**
     * Get user's booking for specific event
     */
    public function getUserEventBooking(int $userId, int $eventId): ?Booking
    {
        return Booking::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();
    }

    /**
     * Get bookings for event paginated
     */
    public function getByEventPaginated(int $eventId, int $perPage = 15): Paginator
    {
        return Booking::where('event_id', $eventId)
            ->with('user')
            ->paginate($perPage);
    }

    /**
     * Get user's bookings paginated
     */
    public function getByUserPaginated(int $userId, int $perPage = 15): Paginator
    {
        return Booking::where('user_id', $userId)
            ->with('event')
            ->paginate($perPage);
    }

    /**
     * Get bookings with filters
     */
    public function getFiltered(int $eventId, ?string $sortBy = 'created_at', int $perPage = 15): Paginator
    {
        $allowedColumns = ['created_at', 'updated_at', 'id', 'user_id']; // Define allowed columns for sorting
        $sortBy = in_array($sortBy, $allowedColumns) ? $sortBy : 'created_at'; // Check if sortBy is in whitelist and Fallback to default if invalid
        // Step 3: Use sanitized value for sorting to prevent SQL injection  or XSS attacks

        return Booking::where('event_id', $eventId)
            ->with('user')
            ->orderBy($sortBy, 'desc')
            ->paginate($perPage);
    }
}

/*
 
 CRUD operations
 Query by event and user
 Duplicate booking detection
 Pagination support
 Eager loading relationships
 Filtering and sorting
 Input sanitization to prevent SQL injection

Whitelist approach — only allow specific columns
Default fallback — use safe default if invalid input
Type hints — int $eventId prevents injection there
Laravel Query Builder — parameterized queries for WHERE clauses

*/

<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Pagination\Paginator;
use Exception;

class EventService
{
    public function __construct(private EventRepository $repository) {}

    /**
     * Get all events with pagination and filtering
     */
    public function getAllEvents(int $perPage = 15, ?string $search = null, ?string $eventType = null): Paginator
    {
        return $this->repository->paginate($perPage, $search, $eventType);
    }

    /**
     * Get user's own events
     */
    public function getUserEvents(int $userId, int $perPage = 15): Paginator
    {
        return $this->repository->getUserEvents($userId, $perPage);
    }

    /**
     * Create a new event
     */
    public function createEvent(int $userId, array $data): Event
    {
        $this->validateEventDates($data['start_time'], $data['end_time'] ?? null);

        $data['user_id'] = $userId;
        return $this->repository->create($data);
    }

    /**
     * Update an event
     */
    public function updateEvent(Event $event, array $data): Event
    {
        if (isset($data['start_time']) || isset($data['end_time'])) {
            $this->validateEventDates(
                $data['start_time'] ?? $event->start_time,
                $data['end_time'] ?? $event->end_time
            );
        }

        return $this->repository->update($event->id, $data);
    }

    /**
     * Delete an event
     */
    public function deleteEvent(Event $event): void
    {
        if ($event->bookings()->exists()) {
            throw new Exception('Cannot delete event with existing bookings');
        }

        $this->repository->delete($event->id);
    }

    /**
     * Get event availability
     */
    public function getEventAvailability(Event $event): array
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

    /**
     * Validate event dates
     */
    private function validateEventDates(?string $startTime, ?string $endTime = null): void
    {
        if (!$startTime) {
            throw new Exception('Start time is required');
        }

        $start = strtotime($startTime);
        if ($start === false || $start < time()) {
            throw new Exception('Start time must be in the future');
        }

        if ($endTime) {
            $end = strtotime($endTime);
            if ($end === false || $end <= $start) {
                throw new Exception('End time must be after start time');
            }
        }
    }
}

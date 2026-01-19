<?php

namespace App\Services;

use App\Models\Attendee;
use App\Models\Event;
use App\Repositories\AttendeeRepository;
use Exception;

class AttendeeService
{
    public function __construct(private AttendeeRepository $repository) {}

    /**
     * Register an attendee for an event (no authentication required)
     */
    public function registerAttendee(int $eventId, array $data): Attendee
    {
        $event = Event::findOrFail($eventId);

        // Validate attendee doesn't already exist
        $this->validateUniqueAttendee($eventId, $data['email']);

        // Check event capacity
        $booked = $event->bookings()->count();
        if ($booked >= $event->venue_capacity) {
            throw new Exception('Event is fully booked');
        }

        $data['event_id'] = $eventId;
        return $this->repository->create($data);
    }

    /**
     * Get all attendees for an event
     */
    public function getEventAttendees(int $eventId): array
    {
        return $this->repository->getByEvent($eventId);
    }

    /**
     * Update attendee information
     */
    public function updateAttendee(Attendee $attendee, array $data): Attendee
    {
        // If email is being changed, validate uniqueness
        if (isset($data['email']) && $data['email'] !== $attendee->email) {
            $this->validateUniqueAttendee($attendee->event_id, $data['email'], $attendee->id);
        }

        return $this->repository->update($attendee->id, $data);
    }

    /**
     * Unregister an attendee
     */
    public function unregisterAttendee(Attendee $attendee): void
    {
        $this->repository->delete($attendee->id);
    }

    /**
     * Get attendee count for an event
     */
    public function getAttendeeCount(int $eventId): int
    {
        return $this->repository->countByEvent($eventId);
    }

    /**
     * Check if email already registered for event
     */
    public function isEmailRegistered(int $eventId, string $email, ?int $excludeId = null): bool
    {
        return $this->repository->emailExists($eventId, $email, $excludeId);
    }

    /**
     * Validate unique attendee email per event
     */
    private function validateUniqueAttendee(int $eventId, string $email, ?int $excludeId = null): void
    {
        if ($this->isEmailRegistered($eventId, $email, $excludeId)) {
            throw new Exception('This email is already registered for this event');
        }
    }
}

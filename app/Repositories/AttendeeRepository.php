<?php

namespace App\Repositories;

use App\Models\Attendee;

class AttendeeRepository
{
    /**
     * Create a new attendee
     */
    public function create(array $data): Attendee
    {
        return Attendee::create($data);
    }

    /**
     * Update an attendee
     */
    public function update(int $id, array $data): Attendee
    {
        $attendee = Attendee::findOrFail($id);
        $attendee->update($data);
        return $attendee;
    }

    /**
     * Delete an attendee
     */
    public function delete(int $id): void
    {
        Attendee::destroy($id);
    }

    /**
     * Get all attendees for an event
     */
    public function getByEvent(int $eventId): array
    {
        return Attendee::where('event_id', $eventId)->get()->toArray();
    }

    /**
     * Count attendees for an event
     */
    public function countByEvent(int $eventId): int
    {
        return Attendee::where('event_id', $eventId)->count();
    }

    /**
     * Check if email exists for event
     */
    public function emailExists(int $eventId, string $email, ?int $excludeId = null): bool
    {
        $query = Attendee::where('event_id', $eventId)
            ->where('email', $email);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}

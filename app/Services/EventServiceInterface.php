<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * EventServiceInterface
 *
 * Contract for event business logic operations
 * Defines all methods that event services must implement
 */
interface EventServiceInterface
{
    /**
     * Retrieve all events with optional search filtering (paginated)
     *
     * @param int $perPage Number of events per page (default: 15)
     * @param string|null $search Optional search query to filter by title or description
     * @return LengthAwarePaginator Paginated collection of events with metadata
     */
    public function getAllEvents(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    /**
     * Retrieve events owned by a specific user (paginated)
     *
     * @param int $userId The ID of the user whose events to retrieve
     * @param int $perPage Number of events per page (default: 15)
     * @return LengthAwarePaginator Paginated collection of user's events with metadata
     */
    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new event for a user
     *
     * @param int $userId The ID of the user creating the event
     * @param array $data Event attributes (title, description, location, start_time, end_time, capacity, price)
     * @return Event The newly created event
     */
    public function createEvent(int $userId, array $data): Event;

    /**
     * Update an existing event
     *
     * @param Event $event The event instance to update
     * @param array $data Updated event attributes (can be partial)
     * @return Event The updated event
     */
    public function updateEvent(Event $event, array $data): Event;

    /**
     * Delete an event
     *
     * @param Event $event The event instance to delete
     * @return void
     */
    public function deleteEvent(Event $event): void;

    /**
     * Retrieve a single event by ID
     *
     * @param int $id The event ID
     * @return Event The event
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If event is not found
     */
    public function getEventById(int $id): Event;
}

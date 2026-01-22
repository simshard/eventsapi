<?php

namespace App\Services;

use App\Repositories\EventRepositoryInterface;
use App\Models\Event;
use App\Exceptions\EventNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

/**
 * EventService
 *
 * Handles all event business logic with error handling
 */
class EventService implements EventServiceInterface
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Retrieve all events with optional search filtering (paginated)
     *
     * @param int $perPage Number of events per page (default: 15)
     * @param string|null $search Optional search query to filter by title or description
     * @return LengthAwarePaginator Paginated collection of events with metadata
     */
    public function getAllEvents(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        try {
            Log::info('Fetching all events', ['per_page' => $perPage, 'search' => $search]);
            return $this->eventRepository->paginate($perPage, $search);
        } catch (\Exception $e) {
            Log::error('Error fetching events', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Retrieve events owned by a specific user (paginated)
     *
     * @param int $userId The ID of the user whose events to retrieve
     * @param int $perPage Number of events per page (default: 15)
     * @return LengthAwarePaginator Paginated collection of user's events with metadata
     */
    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        try {
            Log::info('Fetching user events', ['user_id' => $userId, 'per_page' => $perPage]);
            return $this->eventRepository->getUserEvents($userId, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching user events', ['user_id' => $userId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Create a new event for a user
     *
     * @param int $userId The ID of the user creating the event
     * @param array $data Event attributes (title, description, location, start_time, end_time, venue_capacity, price)
     * @return Event The newly created event
     * @throws \Exception If event creation fails
     */
    public function createEvent(int $userId, array $data): Event
    {
        try {
            Log::info('Creating new event', ['user_id' => $userId, 'title' => $data['title'] ?? null]);

            $data['user_id'] = $userId;
            $event = $this->eventRepository->create($data);

            Log::info('Event created successfully', ['event_id' => $event->id, 'user_id' => $userId]);

            return $event;
        } catch (\Exception $e) {
            Log::error('Error creating event', ['user_id' => $userId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update an existing event
     *
     * @param Event $event The event instance to update
     * @param array $data Updated event attributes (can be partial)
     * @return Event The updated event
     * @throws EventNotFoundException If event is not found
     */
    public function updateEvent(Event $event, array $data): Event
    {
        try {
            Log::info('Updating event', ['event_id' => $event->id]);

            $event = $this->eventRepository->update($event->id, $data);

            Log::info('Event updated successfully', ['event_id' => $event->id]);

            return $event;
        } catch (\Exception $e) {
            Log::error('Error updating event', ['event_id' => $event->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete an event
     *
     * @param Event $event The event instance to delete
     * @return void
     * @throws EventNotFoundException If event is not found
     */
    public function deleteEvent(Event $event): void
    {
        try {
            Log::info('Deleting event', ['event_id' => $event->id]);

            $this->eventRepository->delete($event->id);

            Log::info('Event deleted successfully', ['event_id' => $event->id]);
        } catch (\Exception $e) {
            Log::error('Error deleting event', ['event_id' => $event->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Retrieve a single event by ID
     *
     * @param int $id The event ID
     * @return Event The event
     * @throws EventNotFoundException If event is not found
     */
    public function getEventById(int $id): Event
    {
        try {
            Log::info('Fetching event by ID', ['event_id' => $id]);

            $event = $this->eventRepository->findById($id);

            if (!$event) {
                throw new EventNotFoundException($id);
            }

            return $event;
        } catch (EventNotFoundException $e) {
            Log::warning('Event not found', ['event_id' => $id]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error fetching event', ['event_id' => $id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}

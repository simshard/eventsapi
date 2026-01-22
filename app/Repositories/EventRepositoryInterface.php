<?php

namespace App\Services;
use App\Repositories\EventRepositoryInterface;
use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;

class EventService implements EventServiceInterface
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Retrieve paginated events with optional search filtering
     *
     * @param int $perPage Number of events per page
     * @param string|null $search Optional search query
     * @return LengthAwarePaginator
     */
    public function getAllEvents(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->eventRepository->paginate($perPage, $search);
    }

    /**
     * Retrieve paginated events owned by a specific user
     *
     * @param int $userId The user ID
     * @param int $perPage Number of events per page
     * @return LengthAwarePaginator
     */
    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->eventRepository->getUserEvents($userId, $perPage);
    }

    /**
     * Create a new event for a user
     *
     * @param int $userId The user ID creating the event
     * @param array $data Event attributes
     * @return Event The created event
     */
    public function createEvent(int $userId, array $data): Event
    {
        $data['user_id'] = $userId;
        return $this->eventRepository->create($data);
    }

    /**
     * Update an existing event
     *
     * @param Event $event The event to update
     * @param array $data Updated event attributes
     * @return Event The updated event
     */
    public function updateEvent(Event $event, array $data): Event
    {
        return $this->eventRepository->update($event->id, $data);
    }

    /**
     * Delete an event
     *
     * @param Event $event The event to delete
     * @return void
     */
    public function deleteEvent(Event $event): void
    {
        $this->eventRepository->delete($event->id);
    }

    /**
     * Retrieve an event by ID
     *
     * @param int $id The event ID
     * @return Event The event
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getEventById(int $id): Event
    {
        return $this->eventRepository->findById($id);
    }
}

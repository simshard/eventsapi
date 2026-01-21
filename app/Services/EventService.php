<?php

namespace App\Services;

use App\Repositories\EventRepository;
use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;

class EventService
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getAllEvents(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->eventRepository->paginate($perPage, $search);
    }

    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->eventRepository->getUserEvents($userId, $perPage);
    }

    public function createEvent(int $userId, array $data): Event
    {
        $data['user_id'] = $userId;
        return $this->eventRepository->create($data);
    }

    public function updateEvent(Event $event, array $data): Event
    {
        return $this->eventRepository->update($event->id, $data);
    }

    public function deleteEvent(Event $event): void
    {
        $this->eventRepository->delete($event->id);
    }

    public function getEventById(int $id): Event
    {
        return $this->eventRepository->findById($id);
    }
}

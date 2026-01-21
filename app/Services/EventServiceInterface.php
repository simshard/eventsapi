<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventServiceInterface
{
    public function getAllEvents(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function createEvent(int $userId, array $data): Event;

    public function updateEvent(Event $event, array $data): Event;

    public function deleteEvent(Event $event): void;

    public function getEventById(int $id): Event;
}

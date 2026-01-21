<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EventRepository
{
    /**
     * Create a new event
     */
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Get event by ID
     */
    public function findById(int $id): Event
    {
        return Event::findOrFail($id);
    }

    /**
     * Update an event
     */
    public function update(int $id, array $data): Event
    {
        $event = Event::findOrFail($id);
        $event->update($data);
        return $event;
    }

    /**
     * Delete an event
     */
    public function delete(int $id): void
    {
        Event::destroy($id);
    }

    /**
     * Get all events paginated
     */
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = Event::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        return $query->with('user')->paginate($perPage);
    }

    /**
     * Get user's events paginated
     */
    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Event::where('user_id', $userId)
            ->with('bookings')
            ->paginate($perPage);
    }

    /**
     * Get all events (no pagination)
     */
    public function all(): Collection
    {
        return Event::with('user', 'bookings')->get();
    }

    /**
     * Get events by user (no pagination)
     */
    public function getByUser(int $userId): Collection
    {
        return Event::where('user_id', $userId)
            ->with('bookings')
            ->get();
    }

    /**
     * Count events for a user
     */
    public function countByUser(int $userId): int
    {
        return Event::where('user_id', $userId)->count();
    }

    /**
     * Get events with filters and sorting
     */
    public function getFiltered(
        ?string $sortBy = 'created_at',
        ?string $search = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $allowedColumns = ['created_at', 'updated_at', 'id', 'title', 'start_time'];
        $sortBy = in_array($sortBy, $allowedColumns) ? $sortBy : 'created_at';

        $query = Event::with('user', 'bookings');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        return $query->orderBy($sortBy, 'desc')->paginate($perPage);
    }

    /**
     * Check if event exists
     */
    public function exists(int $id): bool
    {
        return Event::where('id', $id)->exists();
    }

    /**
     * Get upcoming events
     */
    public function getUpcoming(int $perPage = 15): LengthAwarePaginator
    {
        return Event::where('start_time', '>', now())
            ->with('user', 'bookings')
            ->orderBy('start_time', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get past events
     */
    public function getPast(int $perPage = 15): LengthAwarePaginator
    {
        return Event::where('start_time', '<', now())
            ->with('user', 'bookings')
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);
    }
}

/*
Key Features
CRUD Operations:

create(), findById(), update(), delete()
 Query Methods:

paginate() — all events with search & filtering
getUserEvents() — user's events paginated
getByUser() — user's events (no pagination)
all() — all events
 Advanced Features:

getFiltered() — with sorting & SQL injection protection
countByUser() — count user's events
exists() — check if event exists
getUpcoming() — events starting in future
getPast() — events already finished
Eager Loading:

.with('user', 'bookings') — loads relationships to prevent N+1 queries
Security:

Whitelist approach in getFiltered() for sorting

*/

<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * EventRepository
 *
 * Concrete implementation of EventRepositoryInterface
 * Handles all event data access operations
 */
class EventRepository implements EventRepositoryInterface
{
    /**
     * Create a new event
     *
     * @param array $data Event attributes
     * @return Event The created event
     */
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Retrieve an event by ID
     *
     * @param int $id The event ID
     * @return Event The event
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id): Event
    {
        return Event::findOrFail($id);
    }

    /**
     * Update an existing event
     *
     * @param int $id The event ID
     * @param array $data Updated event attributes
     * @return Event The updated event
     */
    public function update(int $id, array $data): Event
    {
        $event = Event::findOrFail($id);
        $event->update($data);
        return $event;
    }

    /**
     * Delete an event
     *
     * @param int $id The event ID
     * @return void
     */
    public function delete(int $id): void
    {
        Event::destroy($id);
    }

    /**
     * Get all events with optional search filtering (paginated)
     *
     * @param int $perPage Number of events per page
     * @param string|null $search Optional search query for title and description
     * @return LengthAwarePaginator
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
     * Get events owned by a specific user (paginated)
     *
     * @param int $userId The user ID
     * @param int $perPage Number of events per page
     * @return LengthAwarePaginator
     */
    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Event::where('user_id', $userId)
            ->with('bookings')
            ->paginate($perPage);
    }

    /**
     * Get all events (unpaginated)
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Event::with('user', 'bookings')->get();
    }

    /**
     * Get all events owned by a specific user (unpaginated)
     *
     * @param int $userId The user ID
     * @return Collection
     */
    public function getByUser(int $userId): Collection
    {
        return Event::where('user_id', $userId)
            ->with('bookings')
            ->get();
    }

    /**
     * Count events owned by a specific user
     *
     * @param int $userId The user ID
     * @return int Total count of user's events
     */
    public function countByUser(int $userId): int
    {
        return Event::where('user_id', $userId)->count();
    }

    /**
     * Get events with advanced filtering, searching, and sorting
     *
     * @param string|null $sortBy Column to sort by (allowed: created_at, updated_at, id, title, start_time)
     * @param string|null $search Optional search query for title and description
     * @param int $perPage Number of events per page
     * @return LengthAwarePaginator
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
     * Check if an event exists by ID
     *
     * @param int $id The event ID
     * @return bool True if event exists, false otherwise
     */
    public function exists(int $id): bool
    {
        return Event::where('id', $id)->exists();
    }

    /**
     * Get upcoming events (paginated)
     *
     * @param int $perPage Number of events per page
     * @return LengthAwarePaginator
     */
    public function getUpcoming(int $perPage = 15): LengthAwarePaginator
    {
        return Event::where('start_time', '>', now())
            ->with('user', 'bookings')
            ->orderBy('start_time', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get past events (paginated)
     *
     * @param int $perPage Number of events per page
     * @return LengthAwarePaginator
     */
    public function getPast(int $perPage = 15): LengthAwarePaginator
    {
        return Event::where('start_time', '<', now())
            ->with('user', 'bookings')
            ->orderBy('start_time', 'desc')
            ->paginate($perPage);
    }
}

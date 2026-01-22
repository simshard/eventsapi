<?php

namespace App\Repositories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * EventRepositoryInterface
 *
 * Contract for event data access operations
 */
interface EventRepositoryInterface
{
    /**
     * Create a new event
     *
     * @param array $data Event attributes
     * @return Event The created event
     */
    public function create(array $data): Event;

    /**
     * Retrieve an event by ID
     *
     * @param int $id The event ID
     * @return Event The event
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int $id): Event;

    /**
     * Update an existing event
     *
     * @param int $id The event ID
     * @param array $data Updated event attributes
     * @return Event The updated event
     */
    public function update(int $id, array $data): Event;

    /**
     * Delete an event
     *
     * @param int $id The event ID
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Get all events with optional search filtering (paginated)
     *
     * @param int $perPage Number of events per page
     * @param string|null $search Optional search query for title and description
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    /**
     * Get events owned by a specific user (paginated)
     *
     * @param int $userId The user ID
     * @param int $perPage Number of events per page
     * @return LengthAwarePaginator
     */
    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all events (unpaginated)
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all events owned by a specific user (unpaginated)
     *
     * @param int $userId The user ID
     * @return Collection
     */
    public function getByUser(int $userId): Collection;
}

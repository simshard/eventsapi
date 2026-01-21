<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

interface BookingRepositoryInterface
{
    public function create(array $data): Booking;

    public function findById(int $id): Booking;

    public function update(int $id, array $data): Booking;

    public function delete(int $id): void;

    public function getByEvent(int $eventId): Collection;

    public function getByUser(int $userId): Collection;

    public function countByUser(int $userId): int;

    public function countByEvent(int $eventId): int;

    public function userHasBooked(int $userId, int $eventId): bool;

    public function getUserEventBooking(int $userId, int $eventId): ?Booking;

    public function getByEventPaginated(int $eventId, int $perPage = 15): Paginator;

    public function getByUserPaginated(int $userId, int $perPage = 15): Paginator;

    public function getFiltered(int $eventId, ?string $sortBy = 'created_at', int $perPage = 15): Paginator;
}

<?php

namespace App\Repositories;

use App\Models\Attendee;

interface AttendeeRepositoryInterface
{
    public function create(array $data): Attendee;

    public function update(int $id, array $data): Attendee;

    public function delete(int $id): void;

    public function getByEvent(int $eventId): array;

    public function countByEvent(int $eventId): int;

    public function emailExists(int $eventId, string $email, ?int $excludeId = null): bool;
}

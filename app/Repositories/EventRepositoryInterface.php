<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface
{
    public function create(array $data);

    public function findById(int $id);

    public function update(int $id, array $data);

    public function delete(int $id): void;

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    public function getUserEvents(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function all(): Collection;

    public function getByUser(int $userId): Collection;

    public function countByUser(int $userId): int;

    public function getFiltered(
        ?string $sortBy = 'created_at',
        ?string $search = null,
        int $perPage = 15
    ): LengthAwarePaginator;

    public function exists(int $id): bool;

    public function getUpcoming(int $perPage = 15): LengthAwarePaginator;

    public function getPast(int $perPage = 15): LengthAwarePaginator;
}

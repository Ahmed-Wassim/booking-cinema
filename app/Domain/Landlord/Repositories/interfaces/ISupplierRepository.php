<?php

namespace App\Domain\Landlord\Repositories\Interfaces;

use App\Models\Supplier;
use Illuminate\Support\Collection;

interface ISupplierRepository
{
    public function findById(int $id): ?Supplier;

    public function findByKey(string $key): ?Supplier;

    public function listActive(array $relations = []): Collection;

    public function listAllWithRelations(array $relations = [], string $orderBy = 'id', string $orderType = 'ASC'): Collection;
}

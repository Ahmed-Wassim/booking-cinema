<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Branch\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IBranchRepository
{
    public function create(array $data): Model;

    public function listAllBy(
        array $conditions = [],
        array $relations = [],
        array $select = ['*'],
        string $orderBy = 'id',
        string $orderType = 'DESC',
    ): Collection|array;

    public function update(array $data, array $conditions = [], array $select = ['*']): Model;

    public function delete(array $conditions): void;

    public function findOrFail(int $id): Model;
}

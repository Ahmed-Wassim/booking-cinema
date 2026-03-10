<?php

namespace App\Domain\Landlord\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IPlanRepository
{
    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    public function listAllBy(
        array $conditions = [],
        array $relations = [],
        array $select = ['*'],
        string $orderBy = 'id',
        string $orderType = 'DESC',
        array $orConditions = [],
        array $whereHasConditions = [],
        array $whereDoesntHaveConditions = [],
        $location = []
    ): \Illuminate\Support\Collection|array;

    /**
     * @param array $data
     * @param array $conditions
     * @param array $select
     * @return Model
     */
    public function update(array $data, array $conditions = [], array $select = ['*']): Model;

    /**
     * @param array $conditions
     * @return void
     */
    public function delete(array $conditions): void;
}

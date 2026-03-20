<?php

namespace App\Domain\Landlord\Dashboard\Web\Plan\Repositories\Classes;

use App\Domain\Landlord\Dashboard\Web\Plan\Repositories\Interfaces\IPlanRepository;
use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;

class PlanRepository extends AbstractRepository implements IPlanRepository
{
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data, array $conditions = [], array $select = ['*']): Model
    {
        $model = $this->model->where($conditions)->select($select)->firstOrFail();
        $model->update($data);
        return $model;
    }

    public function delete(array $conditions): void
    {
        $model = $this->first(conditions: $conditions);
        if ($model) {
            $model->delete();
        }
    }
}

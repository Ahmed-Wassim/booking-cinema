<?php

namespace App\Domain\Landlord\Dashboard\Web\Supplier\Repositories\Classes;

use App\Domain\Landlord\Dashboard\Web\Supplier\Repositories\Interfaces\ISupplierRepository;
use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Models\Supplier;
use Illuminate\Support\Collection;

class SupplierRepository extends AbstractRepository implements ISupplierRepository
{
    public function findById(int $id): ?Supplier
    {
        $found = $this->model->find($id);

        return $found instanceof Supplier ? $found : null;
    }

    public function findByKey(string $key): ?Supplier
    {
        $found = $this->model->where('key', $key)->first();

        return $found instanceof Supplier ? $found : null;
    }

    public function listActive(array $relations = []): Collection
    {
        return $this->model->active()->with($relations)->get();
    }

    public function listAllWithRelations(array $relations = [], string $orderBy = 'id', string $orderType = 'ASC'): Collection
    {
        return $this->model->with($relations)->orderBy($orderBy, $orderType)->get();
    }
}

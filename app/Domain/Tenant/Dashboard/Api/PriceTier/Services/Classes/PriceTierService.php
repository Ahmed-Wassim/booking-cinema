<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\PriceTier\Repositories\Interfaces\IPriceTierRepository;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Interfaces\IPriceTierService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PriceTierService implements IPriceTierService
{
    public function __construct(
        protected IPriceTierRepository $priceTierRepository
    ) {}

    public function listAllPriceTiers(): Collection|array
    {
        return $this->priceTierRepository->listAllBy(relations: ['hall']);
    }

    public function storePriceTier(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            return $this->priceTierRepository->create($data);
        });
    }

    public function editPriceTier(string|int $id): Model
    {
        return $this->priceTierRepository->findOrFail((int)$id);
    }

    public function updatePriceTier(array $data, string|int $id): Model
    {
        return DB::transaction(function () use ($data, $id) {
            return $this->priceTierRepository->update($data, ['id' => $id]);
        });
    }

    public function deletePriceTier(string|int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $this->priceTierRepository->delete(['id' => $id]);
            return true;
        });
    }
}

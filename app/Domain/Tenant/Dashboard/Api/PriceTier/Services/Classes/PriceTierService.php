<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\PriceTier\Repositories\Interfaces\IPriceTierRepository;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Interfaces\IPriceTierService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PriceTierService implements IPriceTierService
{
    public function __construct(
        protected IPriceTierRepository $priceTierRepository
    ) {}

    public function listAllPriceTiers(): LengthAwarePaginator
    {
        return $this->priceTierRepository->retrieve();
    }

    public function storePriceTier(array $data): Model
    {
        return $this->priceTierRepository->create($data);
    }

    public function editPriceTier(string|int $id): Model
    {
        return $this->priceTierRepository->findOrFail((int) $id);
    }

    public function updatePriceTier(array $data, string|int $id): Model
    {
        return $this->priceTierRepository->update($data, ['id' => $id]);
    }

    public function deletePriceTier(string|int $id): bool
    {
        $this->priceTierRepository->delete(['id' => $id]);

        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IPriceTierService
{
    public function listAllPriceTiers(): LengthAwarePaginator;

    public function storePriceTier(array $data): Model;

    public function editPriceTier(string|int $id): Model;

    public function updatePriceTier(array $data, string|int $id): Model;

    public function deletePriceTier(string|int $id): bool;
}

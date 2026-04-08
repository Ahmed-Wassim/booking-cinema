<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Discount\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Discount\Repositories\Interfaces\IDiscountRepository;
use App\Domain\Tenant\Dashboard\Api\Discount\Services\Interfaces\IDiscountService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class DiscountService implements IDiscountService
{
    public function __construct(
        protected IDiscountRepository $discountRepository
    ) {}

    public function listAllDiscounts(): LengthAwarePaginator
    {
        return $this->discountRepository->retrieve();
    }

    public function storeDiscount(array $data): Model
    {
        return $this->discountRepository->create($data);
    }

    public function editDiscount(string|int $id): Model
    {
        return $this->discountRepository->findOrFail((int) $id);
    }

    public function updateDiscount(array $data, string|int $id): Model
    {
        return $this->discountRepository->update($data, ['id' => $id]);
    }

    public function deleteDiscount(string|int $id): bool
    {
        $this->discountRepository->delete(['id' => $id]);

        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Discount\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IDiscountService
{
    public function listAllDiscounts(): LengthAwarePaginator;

    public function storeDiscount(array $data): Model;

    public function editDiscount(string|int $id): Model;

    public function updateDiscount(array $data, string|int $id): Model;

    public function deleteDiscount(string|int $id): bool;
}

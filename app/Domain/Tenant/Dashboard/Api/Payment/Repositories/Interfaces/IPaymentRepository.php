<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Payment\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IPaymentRepository
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator;
}

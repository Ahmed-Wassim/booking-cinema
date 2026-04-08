<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Payment\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IPaymentService
{
    public function listAllPayments(): LengthAwarePaginator;
}

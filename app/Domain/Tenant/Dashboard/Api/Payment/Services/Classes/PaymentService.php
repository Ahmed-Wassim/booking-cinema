<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Payment\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Payment\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Tenant\Dashboard\Api\Payment\Services\Interfaces\IPaymentService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentService implements IPaymentService
{
    public function __construct(
        protected IPaymentRepository $paymentRepository
    ) {}

    public function listAllPayments(): LengthAwarePaginator
    {
        return $this->paymentRepository->paginateWithRelations(
            max(1, (int) request('paginate', 15))
        );
    }
}

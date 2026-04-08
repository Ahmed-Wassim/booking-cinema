<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Payment\Repositories\Classes;

use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Domain\Tenant\Dashboard\Api\Payment\Repositories\Interfaces\IPaymentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository extends AbstractRepository implements IPaymentRepository
{
    public function paginateWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with([
                'booking.customer',
                'booking.user',
                'booking.showtime.movie',
                'booking.showtime.hall',
            ])
            ->latest()
            ->paginate($perPage);
    }
}

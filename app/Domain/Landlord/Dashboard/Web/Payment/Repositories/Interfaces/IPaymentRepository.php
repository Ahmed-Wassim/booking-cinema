<?php

namespace App\Domain\Landlord\Dashboard\Web\Payment\Repositories\Interfaces;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

interface IPaymentRepository
{
    public function findByToken(string $token): ?Payment;

    public function findByRef(string $ref): ?Payment;

    public function getByTenant(string $tenantId);

    public function updateStatus(int $id, string $status, array $extra = []): Model;
}

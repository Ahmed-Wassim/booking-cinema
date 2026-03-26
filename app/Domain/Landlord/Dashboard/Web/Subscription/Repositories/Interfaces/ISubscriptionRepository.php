<?php

namespace App\Domain\Landlord\Dashboard\Web\Subscription\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ISubscriptionRepository
{
    public function findPendingByTenantAndPlan(string $tenantId, int $planId): ?Model;

    public function activate(int $id, ?int $paymentId = null): Model;
}

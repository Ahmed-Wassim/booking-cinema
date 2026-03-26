<?php

namespace App\Domain\Landlord\Dashboard\Web\Subscription\Repositories\Classes;

use App\Domain\Landlord\Dashboard\Web\Subscription\Repositories\Interfaces\ISubscriptionRepository;
use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRepository extends AbstractRepository implements ISubscriptionRepository
{
    public function findPendingByTenantAndPlan(string $tenantId, int $planId): ?Model
    {
        return $this->model
            ->where('tenant_id', $tenantId)
            ->where('plan_id', $planId)
            ->where('status', Subscription::STATUS_PENDING)
            ->latest()
            ->first();
    }

    public function activate(int $id, ?int $paymentId = null): Model
    {
        $subscription = $this->model->findOrFail($id);
        $subscription->update([
            'status' => Subscription::STATUS_ACTIVE,
            'start_date' => now(),
            'payment_id' => $paymentId,
        ]);
        return $subscription;
    }
}

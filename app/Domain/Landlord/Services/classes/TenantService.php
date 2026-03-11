<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Services\Classes;

use App\Domain\Landlord\Repositories\Interfaces\ITenantRepository;
use App\Domain\Landlord\Services\Interfaces\ITenantService;
use Exception;
use Illuminate\Support\Facades\DB;

class TenantService implements ITenantService
{
    public function __construct(
        protected ITenantRepository $tenantRepository
    ) {
    }

    public function listAllTenants()
    {
        return $this->tenantRepository->retrieve(relations: ['domains', 'subscriptions.plan']);
    }

    public function storeTenant(array $data)
    {
        try {
            $tenant = $this->tenantRepository->create([
                'id' => $data['id'],
            ]);

            $tenant->domains()->create([
                'domain' => $data['domain'],
            ]);

            if (isset($data['plan_id'])) {
                $tenant->subscriptions()->create([
                    'plan_id' => $data['plan_id'],
                    'start_date' => now(),
                    'status' => 'active',
                ]);
            }

            return $tenant;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function editTenant(string $id)
    {
        return $this->tenantRepository->firstOrFail(['id' => $id], ['domains', 'subscriptions']);
    }

    public function updateTenant(array $data, string $id)
    {
        try {
            $tenant = $this->tenantRepository->update([
                'id' => $data['id'],
            ], ['id' => $id]);

            $tenant->domains()->update([
                'domain' => $data['domain'],
            ]);

            if (isset($data['plan_id'])) {
                $activeSubscription = $tenant->subscriptions()->where('status', 'active')->first();
                if ($activeSubscription) {
                    $activeSubscription->update(['plan_id' => $data['plan_id']]);
                } else {
                    $tenant->subscriptions()->create([
                        'plan_id' => $data['plan_id'],
                        'start_date' => now(),
                        'status' => 'active',
                    ]);
                }
            }

            return $tenant;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteTenant(string $id)
    {
        return $this->tenantRepository->delete(['id' => $id]);
    }
}

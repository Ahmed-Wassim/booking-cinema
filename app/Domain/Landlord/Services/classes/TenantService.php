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
    ) {}

    public function listAllTenants()
    {
        return $this->tenantRepository->retrieve(relations: ['domains']);
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

            return $tenant;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function editTenant(string $id)
    {
        return $this->tenantRepository->firstOrFail(['id' => $id], ['domains']);
    }

    public function updateTenant(array $data, string $id)
    {
        DB::beginTransaction();
        try {
            $tenant = $this->tenantRepository->update([
                'id' => $data['id'],
            ], ['id' => $id]);

            $tenant->domains()->update([
                'domain' => $data['domain'],
            ]);

            DB::commit();

            return $tenant;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteTenant(string $id)
    {
        return $this->tenantRepository->delete(['id' => $id]);
    }
}

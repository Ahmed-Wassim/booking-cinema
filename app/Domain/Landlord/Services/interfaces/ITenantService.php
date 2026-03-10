<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Services\Interfaces;

interface ITenantService
{
    public function listAllTenants();

    public function storeTenant(array $data);

    public function editTenant(string $id);

    public function updateTenant(array $data, string $id);

    public function deleteTenant(string $id);
}

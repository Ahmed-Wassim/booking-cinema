<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Role\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IRoleService
{
    public function listAllRoles(): LengthAwarePaginator;

    public function storeRole(array $data): Model;

    public function editRole(string $id): Model;

    public function updateRole(array $data, string $id): Model;

    public function deleteRole(string $id): void;
    
    public function getPermissionsGrouped(): \Illuminate\Support\Collection;
}

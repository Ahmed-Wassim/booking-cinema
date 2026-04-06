<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Role\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class RoleDTO extends DataTransferObject
{
    public string $name;
    public string $guard_name;
    public array $permissions = [];

    public static function fromRequest(array $request): self
    {
        return new self([
            'name' => $request['name'],
            'guard_name' => $request['guard_name'] ?? 'tenant',
            'permissions' => $request['permissions'] ?? [],
        ]);
    }
}

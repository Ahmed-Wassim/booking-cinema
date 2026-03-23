<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\User\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class UserDTO extends DataTransferObject
{
    public string $name;

    public string $email;

    public ?string $password = null;

    public static function fromRequest(array $request): self
    {
        return new self([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'] ?? null,
        ]);
    }
}

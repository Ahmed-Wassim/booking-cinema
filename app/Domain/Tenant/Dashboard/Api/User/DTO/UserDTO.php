<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\User\DTO;

class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null,
    ) {}

    public static function fromRequest(array $request): self
    {
        return new self(
            name: $request['name'],
            email: $request['email'],
            password: $request['password'] ?? null,
        );
    }
}

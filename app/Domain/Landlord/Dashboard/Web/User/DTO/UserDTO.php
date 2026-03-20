<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Dashboard\Web\User\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class UserDTO extends DataTransferObject
{
    public ?string $name;

    public ?string $email;

    public ?string $password;

    public static function fromRequest(array $data): self
    {
        return new self([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
        ]);
    }
}

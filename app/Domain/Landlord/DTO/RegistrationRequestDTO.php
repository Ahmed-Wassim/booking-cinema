<?php

declare(strict_types=1);

namespace App\Domain\Landlord\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class RegistrationRequestDTO extends DataTransferObject
{
    public ?string $company_name;
    public ?string $domain;
    public ?string $name;
    public ?string $email;
    public ?string $password;

    public static function fromRequest(array $data): self
    {
        return new self([
            'company_name' => $data['company_name'] ?? null,
            'domain' => $data['domain'] ?? null,
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
        ]);
    }
}

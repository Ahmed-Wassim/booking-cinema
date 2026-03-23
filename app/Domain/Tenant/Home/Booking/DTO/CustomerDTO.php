<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class CustomerDTO extends DataTransferObject
{
    public string $name;

    public string $email;

    public ?string $phoneCountryCode = null;

    public ?string $phone = null;

    public static function fromRequest(array $data): self
    {
        return new self([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_country_code' => $data['phone_country_code'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);
    }
}

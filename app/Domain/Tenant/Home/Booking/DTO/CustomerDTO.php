<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class CustomerDTO extends DataTransferObject
{
    public string $name;

    public string $email;

    public ?string $phone_country_code;

    public ?string $phone;

    public static function fromRequest(array $data): self
    {
        // dd($data);

        return new self([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_country_code' => $data['phone_country_code'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);
    }
}

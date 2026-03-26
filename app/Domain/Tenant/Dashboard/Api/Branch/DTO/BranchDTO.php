<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Branch\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class BranchDTO extends DataTransferObject
{
    public ?string $name;
    public ?string $city;
    public ?string $address;
    public ?string $timezone;
    public ?bool $is_active;

    public static function fromRequest(array $data): self
    {
        return new self([
            'name'      => $data['name'] ?? null,
            'city'      => $data['city'] ?? null,
            'address'   => $data['address'] ?? null,
            'timezone'  => $data['timezone'] ?? 'UTC',
            'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : true,
        ]);
    }
}

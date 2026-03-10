<?php

declare(strict_types=1);

namespace App\Domain\Landlord\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class PlanDTO extends DataTransferObject
{
    public ?string $name;

    public ?string $description;

    public ?float $price;

    public ?string $billing_interval;

    public ?array $features;

    public static function fromRequest(array $data): self
    {
        return new self([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => isset($data['price']) ? (float) $data['price'] : null,
            'billing_interval' => $data['billing_interval'] ?? null,
            'features' => $data['features'] ?? [],
        ]);
    }
}

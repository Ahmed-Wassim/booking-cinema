<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\PriceTier\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class PriceTierDTO extends DataTransferObject
{
    public ?int $hall_id;
    public ?string $name;
    public ?float $price;
    public ?string $color;
    public ?string $description;
    public ?bool $is_active;

    public static function fromRequest(array $data): self
    {
        return new self([
            'hall_id'     => isset($data['hall_id']) ? (int)$data['hall_id'] : null,
            'name'        => $data['name'] ?? null,
            'price'       => isset($data['price']) ? (float)$data['price'] : null,
            'color'       => $data['color'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active'   => isset($data['is_active']) ? (bool)$data['is_active'] : true,
        ]);
    }
}

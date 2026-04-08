<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Discount\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class DiscountDTO extends DataTransferObject
{
    public ?string $code;

    public ?string $type;

    public ?float $value;

    public ?int $max_uses;

    public ?string $starts_at;

    public ?string $expires_at;

    public ?bool $is_active;

    public static function fromRequest(array $data): self
    {
        return new self([
            'code'       => $data['code'] ?? null,
            'type'       => $data['type'] ?? 'percentage',
            'value'      => isset($data['value']) ? (float) $data['value'] : null,
            'max_uses'   => isset($data['max_uses']) ? (int) $data['max_uses'] : null,
            'starts_at'  => $data['starts_at'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active'  => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ]);
    }
}

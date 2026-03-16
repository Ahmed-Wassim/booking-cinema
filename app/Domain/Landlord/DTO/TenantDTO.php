<?php

declare(strict_types=1);

namespace App\Domain\Landlord\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class TenantDTO extends DataTransferObject
{
    public string $id;

    public string $domain;

    public ?int $plan_id;

    public static function fromRequest(array $data): self
    {
        return new self([
            'id' => $data['id'],
            'domain' => $data['domain'],
            'plan_id' => isset($data['plan_id']) ? (int) $data['plan_id'] : null,
        ]);
    }
}

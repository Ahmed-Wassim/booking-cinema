<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Hall\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class HallDTO extends DataTransferObject
{
    public ?int $branch_id;
    public ?string $name;
    public ?string $type;
    public ?int $total_seats;
    public ?string $layout_type;
    public ?array $base_config;

    public static function fromRequest(array $data): self
    {
        return new self([
            'branch_id'   => isset($data['branch_id']) ? (int)$data['branch_id'] : null,
            'name'        => $data['name'] ?? null,
            'type'        => $data['type'] ?? null,
            'total_seats' => isset($data['total_seats']) ? (int)$data['total_seats'] : 0,
            'layout_type' => $data['layout_type'] ?? null,
            'base_config' => $data['base_config'] ?? null,
        ]);
    }
}

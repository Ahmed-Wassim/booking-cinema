<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\HallSection\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class HallSectionDTO extends DataTransferObject
{
    public ?int $hall_id;
    public ?string $name;
    public ?string $layout_type;
    public ?array $base_config;
    public ?int $sort_order;

    public static function fromRequest(array $data): self
    {
        return new self([
            'hall_id'     => isset($data['hall_id']) ? (int)$data['hall_id'] : null,
            'name'        => $data['name'] ?? null,
            'layout_type' => $data['layout_type'] ?? null,
            'base_config' => $data['base_config'] ?? null,
            'sort_order'  => isset($data['sort_order']) ? (int)$data['sort_order'] : 0,
        ]);
    }
}

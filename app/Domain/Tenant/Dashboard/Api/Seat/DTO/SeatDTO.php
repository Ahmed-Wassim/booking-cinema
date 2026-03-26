<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Seat\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class SeatDTO extends DataTransferObject
{
    public ?int $hall_id;
    public ?int $section_id;
    public ?int $price_tier_id;
    public ?string $row;
    public ?string $number;
    public ?float $pos_x;
    public ?float $pos_y;
    public ?float $rotation;
    public ?float $width;
    public ?float $height;
    public ?string $shape;
    public ?int $sort_order;
    public ?bool $is_active;

    public static function fromRequest(array $data): self
    {
        return new self([
            'hall_id'       => isset($data['hall_id']) ? (int)$data['hall_id'] : null,
            'section_id'    => isset($data['section_id']) ? (int)$data['section_id'] : null,
            'price_tier_id' => isset($data['price_tier_id']) ? (int)$data['price_tier_id'] : null,
            'row'           => $data['row'] ?? null,
            'number'        => $data['number'] ?? null,
            'pos_x'         => isset($data['pos_x']) ? (float)$data['pos_x'] : null,
            'pos_y'         => isset($data['pos_y']) ? (float)$data['pos_y'] : null,
            'rotation'      => isset($data['rotation']) ? (float)$data['rotation'] : 0.0,
            'width'         => isset($data['width']) ? (float)$data['width'] : 4.0,
            'height'        => isset($data['height']) ? (float)$data['height'] : 4.0,
            'shape'         => $data['shape'] ?? 'rect',
            'sort_order'    => isset($data['sort_order']) ? (int)$data['sort_order'] : 0,
            'is_active'     => isset($data['is_active']) ? (bool)$data['is_active'] : true,
        ]);
    }
}

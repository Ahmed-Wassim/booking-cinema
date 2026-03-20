<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Showtime\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class ShowtimeDTO extends DataTransferObject
{
    public ?int $movie_id;
    public ?int $hall_id;
    public ?string $start_time;
    public ?int $price_tier_id;
    public ?string $status;

    public static function fromRequest(array $data): self
    {
        return new self([
            'movie_id'      => isset($data['movie_id']) ? (int)$data['movie_id'] : null,
            'hall_id'       => isset($data['hall_id']) ? (int)$data['hall_id'] : null,
            'start_time'    => $data['start_time'] ?? null,
            'price_tier_id' => isset($data['price_tier_id']) ? (int)$data['price_tier_id'] : null,
            'status'        => $data['status'] ?? null,
        ]);
    }
}

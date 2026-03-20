<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Movie\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class MovieDTO extends DataTransferObject
{
    public ?int $movie_id;
    public ?string $status;

    public static function fromRequest(array $data): self
    {
        return new self([
            'movie_id' => isset($data['movie_id']) ? (int)$data['movie_id'] : null,
            'status'   => $data['status'] ?? null,
        ]);
    }
}

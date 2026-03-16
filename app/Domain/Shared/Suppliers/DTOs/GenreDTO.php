<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\DTOs;

final readonly class GenreDTO
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            name: (string) ($data['name'] ?? ''),
        );
    }
}

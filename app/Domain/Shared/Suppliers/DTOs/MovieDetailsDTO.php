<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\DTOs;

final readonly class MovieDetailsDTO
{
    public function __construct(
        public ?int $runtime,
        public ?string $language,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            runtime: isset($data['runtime']) ? (int) $data['runtime'] : null,
            language: !empty($data['original_language']) ? (string) $data['original_language'] : null,
        );
    }
}

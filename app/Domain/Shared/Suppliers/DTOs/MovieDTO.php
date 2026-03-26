<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\DTOs;

final readonly class MovieDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $overview,
        public ?string $posterPath,
        public ?string $backdropPath,
        public ?string $releaseDate,
        public ?string $language,
        /** @var array<int> */
        public array $genreIds,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $genreIds = $data['genre_ids'] ?? [];
        if (!is_array($genreIds)) {
            $genreIds = [];
        }

        return new self(
            id: (int) ($data['id'] ?? 0),
            title: (string) ($data['title'] ?? 'Unknown'),
            overview: isset($data['overview']) && $data['overview'] !== '' ? (string) $data['overview'] : null,
            posterPath: !empty($data['poster_path']) ? (string) $data['poster_path'] : null,
            backdropPath: !empty($data['backdrop_path']) ? (string) $data['backdrop_path'] : null,
            releaseDate: !empty($data['release_date']) ? (string) $data['release_date'] : null,
            language: !empty($data['original_language']) ? (string) $data['original_language'] : null,
            genreIds: array_map('intval', array_values($genreIds)),
        );
    }
}

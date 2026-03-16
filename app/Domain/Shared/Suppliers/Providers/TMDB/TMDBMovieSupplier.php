<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Providers\TMDB;

use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\DTOs\GenreDTO;
use App\Domain\Shared\Suppliers\DTOs\MovieDetailsDTO;
use App\Domain\Shared\Suppliers\DTOs\MovieDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TMDBMovieSupplier implements MovieSupplier
{
    private const IMAGE_SIZE_POSTER = 'w500';
    private const IMAGE_SIZE_BACKDROP = 'w780';
    private const ENDPOINTS = ['movie/now_playing', 'movie/popular', 'movie/upcoming'];

    public function __construct(
        protected string $apiKey,
        protected string $baseUrl,
        protected string $imageBaseUrl = 'https://image.tmdb.org/t/p'
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->imageBaseUrl = rtrim($imageBaseUrl, '/');
    }

    public function fetchMovies(int $page = 1): array
    {
        $all = [];
        $seenIds = [];

        foreach (self::ENDPOINTS as $endpoint) {
            $response = Http::get("{$this->baseUrl}/{$endpoint}", [
                'api_key' => $this->apiKey,
                'page'    => $page,
            ]);
            if (!$response->successful()) {
                Log::warning('Movie supplier fetch movies failed', [
                    'supplier'  => 'tmdb',
                    'endpoint'  => $endpoint,
                    'page'      => $page,
                    'status'    => $response->status(),
                ]);
                continue;
            }
            $data = $response->json();
            $results = $data['results'] ?? [];
            foreach ($results as $item) {
                $id = (int) ($item['id'] ?? 0);
                if ($id && !isset($seenIds[$id])) {
                    $seenIds[$id] = true;
                    $all[] = MovieDTO::fromArray($item);
                }
            }
        }

        return $all;
    }

    public function fetchGenres(): array
    {
        $response = Http::get("{$this->baseUrl}/genre/movie/list", [
            'api_key' => $this->apiKey,
        ]);
        if (!$response->successful()) {
            Log::error('Movie supplier fetch genres failed', [
                'supplier' => 'tmdb',
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
            return [];
        }
        $data = $response->json();
        $genres = $data['genres'] ?? [];
        return array_map(fn (array $g) => GenreDTO::fromArray($g), $genres);
    }

    public function fetchMovieDetails(string $externalId): ?MovieDetailsDTO
    {
        $response = Http::get("{$this->baseUrl}/movie/{$externalId}", [
            'api_key' => $this->apiKey,
        ]);
        if (!$response->successful()) {
            Log::debug('Movie supplier fetch details failed', [
                'supplier'    => 'tmdb',
                'external_id' => $externalId,
                'status'     => $response->status(),
            ]);
            return null;
        }
        $data = $response->json();
        return MovieDetailsDTO::fromArray($data);
    }

    public function posterUrl(?string $posterPath): ?string
    {
        if ($posterPath === null || $posterPath === '') {
            return null;
        }
        $path = ltrim($posterPath, '/');
        return $this->imageBaseUrl . '/' . self::IMAGE_SIZE_POSTER . '/' . $path;
    }

    public function backdropUrl(?string $backdropPath): ?string
    {
        if ($backdropPath === null || $backdropPath === '') {
            return null;
        }
        $path = ltrim($backdropPath, '/');
        return $this->imageBaseUrl . '/' . self::IMAGE_SIZE_BACKDROP . '/' . $path;
    }
}

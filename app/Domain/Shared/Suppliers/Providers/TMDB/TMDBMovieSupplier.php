<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Providers\TMDB;

use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\DTOs\GenreDTO;
use App\Domain\Shared\Suppliers\DTOs\MovieDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TMDBMovieSupplier implements MovieSupplier
{
    public const ENDPOINTS = [
        'movie/now_playing',
        'movie/upcoming',
        'movie/popular',
    ];

    public function __construct(
        protected string $apiKey,
        protected string $baseUrl,
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Build the full URL for a given TMDB endpoint.
     * Used by Http::pool in MovieSyncService to fire parallel requests.
     */
    public function endpointUrl(string $endpoint): string
    {
        return "{$this->baseUrl}/{$endpoint}";
    }

    /**
     * Expose the API key so Http::pool can attach it as a query param.
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Fetch a single page from a single endpoint.
     * Aggregation and parallelism are handled by the caller (MovieSyncService).
     */
    public function fetchMovies(string $endpoint, int $page = 1): array
    {
        $response = Http::get("{$this->baseUrl}/{$endpoint}", [
            'api_key' => $this->apiKey,
            'page' => $page,
        ]);

        if (! $response->successful()) {
            Log::warning('TMDB fetchMovies failed', [
                'endpoint' => $endpoint,
                'page' => $page,
                'status' => $response->status(),
            ]);

            return [];
        }

        $results = $response->json('results', []);

        return array_map(fn (array $item) => MovieDTO::fromArray($item), $results);
    }

    public function fetchGenres(): array
    {
        $response = Http::get("{$this->baseUrl}/genre/movie/list", [
            'api_key' => $this->apiKey,
        ]);

        if (! $response->successful()) {
            Log::error('TMDB fetchGenres failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        }

        $genres = $response->json('genres', []);

        return array_map(fn (array $g) => GenreDTO::fromArray($g), $genres);
    }
}

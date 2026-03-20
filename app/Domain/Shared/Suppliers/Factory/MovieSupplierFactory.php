<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Factory;

use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\Providers\IMDb\IMDbMovieSupplier;
use App\Domain\Shared\Suppliers\Providers\TMDB\TMDBMovieSupplier;
use InvalidArgumentException;

class MovieSupplierFactory
{
    /**
     * Create a movie supplier instance by key and settings.
     *
     * @param  array{api_key?: string, api_url?: string, image_base_url?: string}  $settings
     */
    public static function make(string $supplierKey, array $settings = []): MovieSupplier
    {
        $key = strtolower($supplierKey);
        $apiKey = $settings['api_key'] ?? '';
        $apiUrl = $settings['api_url'] ?? config('tmdb.base_url', 'https://api.themoviedb.org/3');

        return match ($key) {
            'tmdb' => new TMDBMovieSupplier($apiKey, $apiUrl),
            'imdb' => new IMDbMovieSupplier($settings),
            default => throw new InvalidArgumentException("Movie supplier [{$supplierKey}] is not supported."),
        };
    }
}

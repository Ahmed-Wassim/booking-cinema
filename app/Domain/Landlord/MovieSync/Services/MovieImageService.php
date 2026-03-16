<?php

declare(strict_types=1);

namespace App\Domain\Landlord\MovieSync\Services;

use App\Domain\Landlord\MovieSync\Contracts\IMovieImageService;
use App\Domain\Shared\FileStorage\Contracts\FileStorageServiceInterface;
use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\DTOs\MovieDTO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MovieImageService implements IMovieImageService
{
    public function __construct(
        protected FileStorageServiceInterface $fileStorageService
    ) {
    }

    public function storeMovieImages(Model $movie, MovieDTO $movieDTO, MovieSupplier $provider): void
    {
        $externalId = (string) $movieDTO->id;

        if ($movieDTO->posterPath) {
            $url = $provider->posterUrl($movieDTO->posterPath);
            if ($url) {
                $ext = pathinfo(ltrim($movieDTO->posterPath, '/'), PATHINFO_EXTENSION) ?: 'jpg';
                $stored = $this->fileStorageService->downloadAndStore(
                    $url,
                    'movies/posters',
                    $externalId . '.' . $ext
                );
                if ($stored) {
                    $movie->update(['local_poster_path' => $stored]);
                } else {
                    Log::warning('Movie image download failed', [
                        'movie_id'     => $movie->id,
                        'external_id'  => $externalId,
                        'type'         => 'poster',
                        'url'          => $url,
                    ]);
                }
            }
        }

        if ($movieDTO->backdropPath) {
            $url = $provider->backdropUrl($movieDTO->backdropPath);
            if ($url) {
                $ext = pathinfo(ltrim($movieDTO->backdropPath, '/'), PATHINFO_EXTENSION) ?: 'jpg';
                $stored = $this->fileStorageService->downloadAndStore(
                    $url,
                    'movies/backdrops',
                    $externalId . '_backdrop.' . $ext
                );
                if ($stored) {
                    $movie->update(['local_backdrop_path' => $stored]);
                } else {
                    Log::warning('Movie image download failed', [
                        'movie_id'     => $movie->id,
                        'external_id'  => $externalId,
                        'type'         => 'backdrop',
                        'url'          => $url,
                    ]);
                }
            }
        }
    }
}

<?php

namespace App\Domain\Tenant\Dashboard\Api\Movie\Services\Classes;

use App\Domain\Landlord\Dashboard\Web\Movie\Repositories\Interfaces\IMovieRepository as ILandlordMovieRepository;
use App\Domain\Shared\Suppliers\Providers\TMDB\TmdbImageUrl;
use App\Domain\Tenant\Dashboard\Api\Movie\Repositories\Interfaces\IMovieRepository;
use App\Domain\Tenant\Dashboard\Api\Movie\Services\Interfaces\IMovieService;
use App\Models\Tenant\Movie;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MovieService implements IMovieService
{
    public function __construct(protected IMovieRepository $movieRepository, protected ILandlordMovieRepository $landlordMovieRepository
    ) {}

    public function addMovieToTenant(array $data): Movie
    {
        $landlordMovieId = $data['movie_id'];
        $landlordMovie = $this->landlordMovieRepository->findWithCondition(['id' => $landlordMovieId]);

        if (! $landlordMovie) {
            throw new Exception('Movie not found in global database.');
        }

        $existingTenantMovie = $this->movieRepository->findWithCondition(['movie_id' => $landlordMovie->id]);
        if ($existingTenantMovie) {
            return $existingTenantMovie;
        }

        return $this->movieRepository->create([
            'movie_id' => $landlordMovie->id,
            'title' => $landlordMovie->title,
            'poster' => TmdbImageUrl::poster($landlordMovie->poster_path),
            'runtime' => $landlordMovie->runtime,
            'status' => 'active',
        ]);
    }

    public function listAllMovies(): LengthAwarePaginator
    {
        return $this->movieRepository->retrieve();
    }

    public function getLandlordMovies(): LengthAwarePaginator
    {
        return $this->landlordMovieRepository->retrieve(
            relations: ['genres'],
            orderBy: 'release_date',
            order: 'DESC'
        );
    }

    public function updateMovie(int $id, array $data): Movie
    {
        return $this->movieRepository->update($data, ['id' => $id]);
    }

    public function deleteMovie(int $id): bool
    {
        $this->movieRepository->delete(['id' => $id]);

        return true;
    }
}

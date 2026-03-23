<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Movie\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Movie\Repositories\Interfaces\IMovieRepository;
use App\Domain\Tenant\Home\Movie\Services\Interfaces\IHomeMovieService;
use App\Models\Tenant\Movie;
use Illuminate\Support\Collection;

class HomeMovieService implements IHomeMovieService
{
    public function __construct(
        protected IMovieRepository $movieRepository
    ) {}

    /**
     * Return all active (now-playing) tenant movies.
     */
    public function listNowPlaying(): Collection
    {
        return collect($this->movieRepository->listAllBy(
            conditions: ['status' => 'active'],
            orderBy:    'id',
            orderType:  'DESC'
        ));
    }

    /**
     * Fetch a single tenant movie by its local id.
     */
    public function getMovieById(int $id): Movie
    {
        return $this->movieRepository->findOrFail($id);
    }
}

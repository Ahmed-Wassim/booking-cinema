<?php

namespace App\Domain\Landlord\Repositories\Interfaces;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;

interface IMovieRepository
{
    public function updateOrCreateBySupplierAndExternal(int $supplierId, int $externalId, array $data): Model;

    public function syncGenres(Movie $movie, array $genreIds): void;
}

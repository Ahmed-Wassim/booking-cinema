<?php

namespace App\Domain\Landlord\Dashboard\Web\Movie\Repositories\Classes;

use App\Domain\Landlord\Dashboard\Web\Movie\Repositories\Interfaces\IMovieRepository;
use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;

class MovieRepository extends AbstractRepository implements IMovieRepository
{
    public function updateOrCreateBySupplierAndExternal(int $supplierId, int $externalId, array $data): Model
    {
        return $this->model->updateOrCreate(
        [
            'supplier_id' => $supplierId,
            'external_id' => $externalId,
        ],
            $data
        );
    }

    public function syncGenres(Movie $movie, array $genreIds): void
    {
        $genreIds = array_filter(array_map('intval', $genreIds));
        $localIds = Genre::whereIn('external_id', $genreIds)->pluck('id')->toArray();
        $movie->genres()->sync($localIds);
    }
}

<?php

namespace App\Domain\Landlord\Repositories\Classes;

use App\Domain\Landlord\Repositories\Interfaces\IGenreRepository;
use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;

class GenreRepository extends AbstractRepository implements IGenreRepository
{
    public function updateOrCreateByExternalId(int $externalId, array $data): Model
    {
        return $this->model->updateOrCreate(
        ['external_id' => $externalId],
            $data
        );
    }
}

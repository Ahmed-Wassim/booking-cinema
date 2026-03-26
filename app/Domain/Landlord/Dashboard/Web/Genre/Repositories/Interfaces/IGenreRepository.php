<?php

namespace App\Domain\Landlord\Dashboard\Web\Genre\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IGenreRepository
{
    public function updateOrCreateByExternalId(int $externalId, array $data): Model;
}

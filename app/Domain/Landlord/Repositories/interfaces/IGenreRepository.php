<?php

namespace App\Domain\Landlord\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IGenreRepository
{
    public function updateOrCreateByExternalId(int $externalId, array $data): Model;
}

<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Movie\Services\Interfaces;

use App\Models\Tenant\Movie;
use Illuminate\Support\Collection;

interface IHomeMovieService
{
    public function listNowPlaying(): Collection;

    public function getMovieById(int $id): Movie;
}

<?php

declare(strict_types=1);

namespace App\Domain\Landlord\MovieSync\Contracts;

use App\Domain\Shared\Suppliers\Contracts\MovieSupplier;
use App\Domain\Shared\Suppliers\DTOs\MovieDTO;
use Illuminate\Database\Eloquent\Model;

interface IMovieImageService
{
    public function storeMovieImages(Model $movie, MovieDTO $movieDTO, MovieSupplier $provider): void;
}

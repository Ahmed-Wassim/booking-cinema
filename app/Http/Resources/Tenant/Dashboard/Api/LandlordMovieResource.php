<?php

namespace App\Http\Resources\Tenant\Dashboard\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Shared\Suppliers\Providers\TMDB\TmdbImageUrl;

class LandlordMovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'overview'     => $this->overview,
            'poster_url'   => TmdbImageUrl::poster($this->poster_path),
            'backdrop_url' => TmdbImageUrl::backdrop($this->backdrop_path),
            'release_date' => $this->release_date,
            'language'     => $this->language,
            'popularity'   => $this->popularity,
            'genres'       => $this->genres->pluck('name'),
        ];
    }
}

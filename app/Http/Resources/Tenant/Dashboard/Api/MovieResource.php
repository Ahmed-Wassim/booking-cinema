<?php

namespace App\Http\Resources\Tenant\Dashboard\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'movie_id'   => $this->movie_id,
            'title'      => $this->title,
            'poster_url' => $this->poster,   // already a full URL, stored at insert time
            'runtime'    => $this->runtime,
            'status'     => $this->status,
        ];
    }
}


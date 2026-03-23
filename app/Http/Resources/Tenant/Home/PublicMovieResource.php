<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicMovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'poster'     => $this->poster,
            'runtime'    => $this->runtime,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}

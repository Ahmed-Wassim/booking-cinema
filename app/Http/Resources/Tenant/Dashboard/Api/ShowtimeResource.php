<?php

namespace App\Http\Resources\Tenant\Dashboard\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowtimeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'movie_id'      => $this->movie_id,
            'hall_id'       => $this->hall_id,
            'start_time'    => $this->start_time,
            'end_time'      => $this->end_time,
            'price_tier_id' => $this->price_tier_id,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

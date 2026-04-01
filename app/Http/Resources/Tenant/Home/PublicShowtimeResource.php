<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicShowtimeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'start_time'       => $this->start_time,
            'end_time'         => $this->end_time,
            'offer_percentage' => $this->offer_percentage,
            'status'           => $this->status,
            'price_tier_id' => $this->price_tier_id,
            'hall'          => [
                'id'     => $this->hall?->id,
                'name'   => $this->hall?->name,
                'branch' => [
                    'id'   => $this->hall?->branch?->id,
                    'name' => $this->hall?->branch?->name,
                    'city' => $this->hall?->branch?->city,
                ],
            ],
            'price_tier' => $this->whenLoaded('priceTier', fn () => [
                'id'    => $this->priceTier->id,
                'name'  => $this->priceTier->name,
                'price' => $this->priceTier->price,
            ]),
        ];
    }
}

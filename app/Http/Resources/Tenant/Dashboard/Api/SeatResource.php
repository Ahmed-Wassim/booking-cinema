<?php

namespace App\Http\Resources\Tenant\Dashboard\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'hall_id'       => $this->hall_id,
            'section_id'    => $this->section_id,
            'price_tier_id' => $this->price_tier_id,
            'row'           => $this->row,
            'number'        => $this->number,
            'pos_x'         => $this->pos_x,
            'pos_y'         => $this->pos_y,
            'rotation'      => $this->rotation,
            'width'         => $this->width,
            'height'        => $this->height,
            'shape'         => $this->shape,
            'sort_order'    => $this->sort_order,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}

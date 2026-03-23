<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowtimeSeatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'showtime_id'    => $this->showtime_id,
            'status'         => $this->status,         // available | reserved | booked
            'reserved_until' => $this->reserved_until,
            'price'          => $this->price,
            'seat'           => [
                'id'         => $this->seat?->id,
                'row'        => $this->seat?->row,
                'number'     => $this->seat?->number,
                'pos_x'      => $this->seat?->pos_x,
                'pos_y'      => $this->seat?->pos_y,
                'rotation'   => $this->seat?->rotation,
                'width'      => $this->seat?->width,
                'height'     => $this->seat?->height,
                'shape'      => $this->seat?->shape,
                'sort_order' => $this->seat?->sort_order,
            ],
        ];
    }
}

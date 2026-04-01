<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'showtime_id'     => $this->showtime_id,
            'subtotal'        => $this->subtotal,
            'discount_amount' => $this->discount_amount,
            'total_price'     => $this->total_price,
            'status'          => $this->status,
            'expires_at'      => $this->expires_at,
            'created_at'      => $this->created_at,
            'discount'        => $this->whenLoaded('discount', fn() => [
                'code'  => $this->discount->code,
                'type'  => $this->discount->type,
                'value' => $this->discount->value,
            ]),
            'showtime'        => new PublicShowtimeResource($this->whenLoaded('showtime')),
            'movie'           => new PublicMovieResource($this->whenLoaded('showtime')?->movie),
            'seats'       => $this->whenLoaded('seats', function () {
                return $this->seats->map(function ($bs) {
                    return [
                        'id'               => $bs->id,
                        'showtime_seat_id' => $bs->showtime_seat_id,
                        'price'            => $bs->price,
                        'seat_label'       => $bs->showtimeSeat?->seat?->row . $bs->showtimeSeat?->seat?->number,
                    ];
                });
            }),
        ];
    }
}

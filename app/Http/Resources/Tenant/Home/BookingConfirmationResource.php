<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingConfirmationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'status'      => $this->status,
            'total_price' => $this->total_price,
            'created_at'  => $this->created_at,
            'payment'     => [
                'transaction_ref' => $this->payment?->transaction_ref,
                'gateway'         => $this->payment?->gateway,
            ],
            'movie'       => [
                'title'  => $this->whenLoaded('showtime')?->movie?->title,
                'poster' => $this->whenLoaded('showtime')?->movie?->poster,
            ],
            'showtime'    => [
                'start_time' => $this->whenLoaded('showtime')?->start_time,
                'hall'       => $this->whenLoaded('showtime')?->hall?->name,
                'branch'     => $this->whenLoaded('showtime')?->hall?->branch?->name,
            ],
            'seats'       => $this->whenLoaded('seats', function () {
                return $this->seats->map(function ($bs) {
                    return $bs->showtimeSeat?->seat?->row . $bs->showtimeSeat?->seat?->number;
                })->join(', ');
            }),
        ];
    }
}

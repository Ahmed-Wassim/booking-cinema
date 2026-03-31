<?php

declare(strict_types=1);

namespace App\Http\Resources\Tenant\Dashboard\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'gateway' => $this->gateway,
            'transaction_ref' => $this->transaction_ref,
            'payload' => $this->payload,
            'booking' => $this->whenLoaded('booking', function (): array {
                return [
                    'id' => $this->booking->id,
                    'status' => $this->booking->status?->value ?? $this->booking->status,
                    'total_price' => $this->booking->total_price,
                    'currency' => $this->booking->currency,
                    'expires_at' => $this->booking->expires_at,
                    'customer' => $this->booking->customer ? [
                        'id' => $this->booking->customer->id,
                        'name' => $this->booking->customer->name,
                        'email' => $this->booking->customer->email,
                        'phone_country_code' => $this->booking->customer->phone_country_code,
                        'phone' => $this->booking->customer->phone,
                    ] : null,
                    'user' => $this->booking->user ? [
                        'id' => $this->booking->user->id,
                        'name' => $this->booking->user->name,
                        'email' => $this->booking->user->email,
                    ] : null,
                    'showtime' => $this->booking->showtime ? [
                        'id' => $this->booking->showtime->id,
                        'start_time' => $this->booking->showtime->start_time,
                        'end_time' => $this->booking->showtime->end_time,
                        'movie' => $this->booking->showtime->movie ? [
                            'id' => $this->booking->showtime->movie->id,
                            'title' => $this->booking->showtime->movie->title,
                            'poster' => $this->booking->showtime->movie->poster,
                            'runtime' => $this->booking->showtime->movie->runtime,
                        ] : null,
                        'hall' => $this->booking->showtime->hall ? [
                            'id' => $this->booking->showtime->hall->id,
                            'name' => $this->booking->showtime->hall->name,
                        ] : null,
                    ] : null,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

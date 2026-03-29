<?php

namespace App\Models\Tenant;

use App\Policies\Tenant\BookingSeatPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(BookingSeatPolicy::class)]
class BookingSeat extends Model
{
    protected $fillable = [
        'booking_id',
        'showtime_seat_id',
        'price',
        'currency',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'currency' => 'string',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function showtimeSeat(): BelongsTo
    {
        return $this->belongsTo(ShowtimeSeat::class);
    }
}

<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingSeat extends Model
{
    protected $fillable = [
        'booking_id',
        'showtime_seat_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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

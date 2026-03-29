<?php

namespace App\Models\Tenant;

use App\Policies\Tenant\TicketPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(TicketPolicy::class)]
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'seat_label',
        'seat_id',
        'ticket_number',
        'qr_code',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Optional: nice accessor
    public function getFullTicketInfoAttribute()
    {
        return "Ticket #{$this->ticket_number} - Seat {$this->seat_label}";
    }
}

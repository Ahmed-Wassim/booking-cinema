<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\Tenant\TicketPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

#[UsePolicy(TicketPolicy::class)]
class Ticket extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['used_at', 'ticket_number'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

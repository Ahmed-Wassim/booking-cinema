<?php

namespace App\Models\Tenant;

use App\Policies\Tenant\CustomerPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(CustomerPolicy::class)]
class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_country_code',
        'phone',
        'last_booking_at',
    ];

    protected $casts = [
        'last_booking_at' => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}

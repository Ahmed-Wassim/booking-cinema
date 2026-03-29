<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\Tenant\PaymentPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(PaymentPolicy::class)]
class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'currency',
        'status',
        'gateway',
        'transaction_ref',
        'payload',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'payload' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}

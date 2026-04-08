<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\Tenant\PaymentPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

#[UsePolicy(PaymentPolicy::class)]
class Payment extends Model
{
    use LogsActivity;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'gateway', 'transaction_ref'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

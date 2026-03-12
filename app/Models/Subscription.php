<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_ACTIVE   = 'active';
    const STATUS_CANCELED = 'canceled';
    const STATUS_EXPIRED  = 'expired';
    const STATUS_TRIAL    = 'trial';

    protected $fillable = [
        'tenant_id',
        'payment_id',
        'plan_id',
        'status',
        'start_date',
        'end_date',
        'trial_end_date',
    ];

    protected $casts = [
        'start_date'     => 'datetime',
        'end_date'       => 'datetime',
        'trial_end_date' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

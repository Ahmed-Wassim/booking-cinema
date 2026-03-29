<?php

namespace App\Models;

use App\Policies\SubscriptionPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;

#[UsePolicy(SubscriptionPolicy::class)]
class Subscription extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

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

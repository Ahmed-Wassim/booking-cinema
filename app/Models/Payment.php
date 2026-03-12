<?php

namespace App\Models;

use App\Domain\Landlord\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_request_id',
        'plan_id',
        'transaction_ref',
        'payment_token',
        'status',
        'amount',
        'currency',
        'callback_data',
    ];

    protected $casts = [
        'callback_data' => 'array',
        'amount' => 'decimal:2',
        'status' => PaymentStatusEnum::class,
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'payment_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', PaymentStatusEnum::PAID);
    }

    public function scopePending($query)
    {
        return $query->where('status', PaymentStatusEnum::PENDING);
    }
}

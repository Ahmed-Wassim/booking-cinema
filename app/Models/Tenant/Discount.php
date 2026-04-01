<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\Tenant\DiscountPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(DiscountPolicy::class)]
class Discount extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value'      => 'decimal:2',
        'max_uses'   => 'integer',
        'used_count' => 'integer',
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isValid(): bool
    {
        return $this->is_active
            && ($this->starts_at === null || $this->starts_at->isPast())
            && ($this->expires_at === null || $this->expires_at->isFuture())
            && ($this->max_uses === null || $this->used_count < $this->max_uses);
    }
}

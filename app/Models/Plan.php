<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\PlanPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(PlanPolicy::class)]
class Plan extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'billing_interval',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

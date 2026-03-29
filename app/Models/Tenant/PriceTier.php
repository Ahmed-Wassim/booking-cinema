<?php

namespace App\Models\Tenant;

use App\Policies\Tenant\PriceTierPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(PriceTierPolicy::class)]
class PriceTier extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'hall_id',
        'name',
        'price',
        'currency',
        'color',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'currency' => 'string',
        'is_active' => 'boolean',
    ];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}

<?php

namespace App\Models\Tenant;

use App\Policies\Tenant\HallSectionPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(HallSectionPolicy::class)]
class HallSection extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'hall_id',
        'name',
        'layout_type',
        'base_config',
        'sort_order',
    ];

    protected $casts = [
        'base_config' => 'array',
        'sort_order'  => 'integer',
    ];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class, 'section_id');
    }
}

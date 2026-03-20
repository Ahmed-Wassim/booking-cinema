<?php

namespace App\Models\Tenant;

use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'hall_id',
        'section_id',
        'price_tier_id',
        'row',
        'number',
        'pos_x',
        'pos_y',
        'rotation',
        'width',
        'height',
        'shape',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'pos_x'      => 'decimal:4',
        'pos_y'      => 'decimal:4',
        'rotation'   => 'decimal:2',
        'width'      => 'decimal:2',
        'height'     => 'decimal:2',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(HallSection::class, 'section_id');
    }

    public function priceTier(): BelongsTo
    {
        return $this->belongsTo(PriceTier::class);
    }
}

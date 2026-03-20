<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HallSection extends Model
{
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

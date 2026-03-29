<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\Tenant\ShowtimeSeatPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(ShowtimeSeatPolicy::class)]
class ShowtimeSeat extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'showtime_id',
        'seat_id',
        'status',
        'reserved_until',
        'price',
        'currency',
    ];

    protected $casts = [
        'reserved_until' => 'datetime',
        'price' => 'decimal:2',
        'currency' => 'string',
    ];

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}

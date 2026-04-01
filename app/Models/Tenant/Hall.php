<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\Tenant\HallPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

#[UsePolicy(HallPolicy::class)]
class Hall extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, HasTranslations, SearchTrait, LogsActivity;

    public array $translatable = [
        'name',
    ];

    protected $fillable = [
        'branch_id',
        'name',
        'type',
        'total_seats',
        'layout_type',
        'base_config',
    ];

    protected $casts = [
        'base_config' => 'array',
        'total_seats' => 'integer',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function priceTiers(): HasMany
    {
        return $this->hasMany(PriceTier::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'type', 'total_seats'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

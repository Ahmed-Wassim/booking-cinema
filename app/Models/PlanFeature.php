<?php

namespace App\Models;

use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'plan_id',
        'feature_key',
        'feature_value',
    ];

    protected $casts = [
        'feature_key' => \App\Domain\Landlord\Enums\FeatureKeyEnum::class,
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}

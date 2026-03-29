<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\Tenant\BranchPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(BranchPolicy::class)]
class Branch extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'name',
        'city',
        'address',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function halls(): HasMany
    {
        return $this->hasMany(Hall::class);
    }
}

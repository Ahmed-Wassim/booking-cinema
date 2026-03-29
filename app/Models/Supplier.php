<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\SupplierPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;

#[UsePolicy(SupplierPolicy::class)]
class Supplier extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'key',
        'name',
        'type',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function setting()
    {
        return $this->hasOne(SupplierSetting::class);
    }

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}

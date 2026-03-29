<?php

namespace App\Models\Tenant;

use App\Policies\Tenant\CurrencyPolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(CurrencyPolicy::class)]
class Currency extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
}

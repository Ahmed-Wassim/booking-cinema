<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Policies\CurrencyPolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(CurrencyPolicy::class)]
class Currency extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $timestamps = false;
}

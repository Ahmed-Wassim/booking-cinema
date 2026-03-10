<?php

declare(strict_types=1);

namespace App\Traits\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait ActiveTrait
{
    public function scopeActive(Builder $builder): void
    {
        if (Schema::hasColumn($this->getTable(), 'status') && request('active')) {
            $builder->where('status', request('active'));
        }
    }
}

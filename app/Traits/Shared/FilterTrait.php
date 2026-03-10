<?php

declare(strict_types=1);

namespace App\Traits\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait FilterTrait
{
    public function scopeFilter(Builder $builder): void
    {
        if (request('filter_column') && request('filter_value')) {
            if (Schema::hasColumn($this->getTable(), request('filter_column'))) {
                $builder->where(request('filter_column'), 'ILIKE', '%'.request('filter_value').'%');
            }
        }
    }
}

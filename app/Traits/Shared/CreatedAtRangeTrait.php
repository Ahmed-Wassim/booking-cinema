<?php

declare(strict_types=1);

namespace App\Traits\Shared;

use Illuminate\Database\Eloquent\Builder;

trait CreatedAtRangeTrait
{
    public function scopeCreatedAtRange(Builder $builder): void
    {
        if (request('start_date') && request('end_date')) {
            $builder->whereBetween('created_at', [request('start_date'), request('end_date')]);
        }
    }
}

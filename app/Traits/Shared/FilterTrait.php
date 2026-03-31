<?php

declare(strict_types=1);

namespace App\Traits\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait FilterTrait
{
    public function scopeFilter(Builder $builder): void
    {
        if (request('filter_column') && request('filter_value')) {
            $column = request('filter_column');

            if (Schema::hasColumn($this->getTable(), $column)) {
                $wrappedColumn = $builder->getQuery()->getGrammar()->wrap($column);
                $filterValue = '%'.Str::of((string) request('filter_value'))->trim().'%';

                if (method_exists($this, 'isTranslatableAttribute') && $this->isTranslatableAttribute($column)) {
                    $locale = app()->getLocale();
                    $fallbackLocale = config('app.fallback_locale', 'en');
                    $bindings = [$locale];
                    $expression = "COALESCE({$wrappedColumn}->>?, '')";

                    if ($fallbackLocale !== $locale) {
                        $expression = "COALESCE({$wrappedColumn}->>?, {$wrappedColumn}->>?, '')";
                        $bindings[] = $fallbackLocale;
                    }

                    $bindings[] = $filterValue;

                    $builder->whereRaw("{$expression} ILIKE ?", $bindings);

                    return;
                }

                $builder->whereRaw("CAST({$wrappedColumn} AS TEXT) ILIKE ?", [$filterValue]);
            }
        }
    }
}

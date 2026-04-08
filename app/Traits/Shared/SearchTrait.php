<?php

declare(strict_types=1);

namespace App\Traits\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait SearchTrait
{
    private array $excludedAttributes = ['password'];

    public function scopeSearch(Builder $query): void
    {
        if (request('search_key')) {
            foreach ($this->getFillable() as $key => $attribute) {
                if (in_array($attribute, $this->excludedAttributes, true)) {
                    continue;
                }

                if ($key === 0) {
                    $this->applySearchConstraint($query, $attribute, request('search_key'));
                    continue;
                }

                $this->applySearchConstraint($query, $attribute, request('search_key'), true);
            }
        }
    }

    private function applySearchConstraint(Builder $query, string $attribute, string $searchValue, bool $useOrWhere = false): void
    {
        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($attribute);
        $whereMethod = $useOrWhere ? 'orWhereRaw' : 'whereRaw';

        if (method_exists($this, 'isTranslatableAttribute') && $this->isTranslatableAttribute($attribute)) {
            $locale = app()->getLocale();
            $fallbackLocale = config('app.fallback_locale', 'en');
            $bindings = [$locale];
            $expression = "COALESCE({$wrappedColumn}->>?, '')";

            if ($fallbackLocale !== $locale) {
                $expression = "COALESCE({$wrappedColumn}->>?, {$wrappedColumn}->>?, '')";
                $bindings[] = $fallbackLocale;
            }

            $bindings[] = '%'.Str::of($searchValue)->trim().'%';

            $query->{$whereMethod}("{$expression} ILIKE ?", $bindings);

            return;
        }

        $query->{$whereMethod}("CAST({$wrappedColumn} AS TEXT) ILIKE ?", ['%'.Str::of($searchValue)->trim().'%']);
    }
}

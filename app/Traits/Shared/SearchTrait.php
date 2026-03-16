<?php

declare(strict_types=1);

namespace App\Traits\Shared;

use Illuminate\Database\Eloquent\Builder;

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
                    $query->where($attribute, 'ILIKE', '%'.request('search_key').'%');
                }
                $query->orWhere($attribute, 'ILIKE', '%'.request('search_key').'%');
            }
        }
    }
}

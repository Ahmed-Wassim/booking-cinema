<?php

declare(strict_types=1);

namespace App\Models\Tenant;

use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use CreatedAtRangeTrait, FilterTrait, SearchTrait;
}

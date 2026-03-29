<?php

namespace App\Models;

use App\Policies\TenantPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use App\Models\Subscription;

#[UsePolicy(TenantPolicy::class)]
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, HasDatabase, HasDomains, SearchTrait;

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

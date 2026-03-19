<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Providers\Injectors;

use App\Domain\Tenant\Dashboard\Api\Branch\Services\Classes\BranchService;
use App\Domain\Tenant\Dashboard\Api\Branch\Services\Interfaces\IBranchService;
use App\Domain\Tenant\Dashboard\Api\Hall\Services\Classes\HallService;
use App\Domain\Tenant\Dashboard\Api\Hall\Services\Interfaces\IHallService;
use App\Domain\Tenant\Dashboard\Api\HallSection\Services\Classes\HallSectionService;
use App\Domain\Tenant\Dashboard\Api\HallSection\Services\Interfaces\IHallSectionService;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Classes\PriceTierService;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Interfaces\IPriceTierService;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Classes\SeatService;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Interfaces\ISeatService;
use App\Providers\AppServiceProvider;

class TenantServicesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IBranchService::class, BranchService::class);
        $this->app->scoped(IHallService::class, HallService::class);
        $this->app->scoped(IPriceTierService::class, PriceTierService::class);
        $this->app->scoped(IHallSectionService::class, HallSectionService::class);
        $this->app->scoped(ISeatService::class, SeatService::class);
    }
}

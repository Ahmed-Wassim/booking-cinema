<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Domain\Landlord\Providers\AppServiceProvider::class,
    App\Domain\Tenant\Dashboard\Api\Providers\TenantAppServiceProvider::class,
    // Paytabscom\Laravel_paytabs\PaypageServiceProvider::class,
];

<?php

declare(strict_types=1);

namespace App\Domain\Shared\Payments\Factory;

use App\Domain\Shared\Payments\Contracts\PaymentGateway;
use App\Domain\Shared\Payments\Gateways\PaytabsGateway;

class PaymentGatewayFactory
{
    /**
     * Create a gateway instance based on a merchant name or configuration.
     * For now only Paytabs is supported.
     */
    public function make(?string $merchant = null): PaymentGateway
    {
        $merchant = $merchant ?? 'paytabs';

        switch (strtolower($merchant)) {
            case 'paytabs':
            default:
                return new PaytabsGateway;
        }
    }
}

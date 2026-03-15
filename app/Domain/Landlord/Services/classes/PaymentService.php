<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Services\Classes;

// legacy service no longer contains any business logic or dependencies


/**
 * @deprecated Use \App\Domain\Landlord\Billing\Payment\Services\SubscriptionPaymentService instead.
 * This class is retained purely for backwards compatibility in case some
 * part of the codebase referenced it directly.  The service container is no
 * longer wired to resolve this concrete class, so behaviour is delegated to
 * the new implementation.
 */
class PaymentService extends \App\Domain\Landlord\Billing\Payment\Services\SubscriptionPaymentService
{
    // nothing left here; all behaviour lives in the parent
}

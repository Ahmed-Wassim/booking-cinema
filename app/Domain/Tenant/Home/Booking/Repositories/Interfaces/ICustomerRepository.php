<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Repositories\Interfaces;

interface ICustomerRepository
{
    /**
     * Create or retrieve a customer based on the provided attributes.
     */
    public function firstOrCreate(array $attributes, array $values = []): mixed;
}

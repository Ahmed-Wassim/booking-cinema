<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Pipes;

use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\ICustomerRepository;
use Closure;

class ResolveCustomer
{
    public function __construct(protected ICustomerRepository $customerRepository) {}

    public function handle(array $data, Closure $next): mixed
    {
        /** @var \App\Domain\Tenant\Home\Booking\DTO\CustomerDTO $customerDTO */
        $customerDTO = $data['customer'];

        $customer = $this->customerRepository->firstOrCreate(
            ['email' => $customerDTO->email],
            [
                'name'               => $customerDTO->name,
                'phone_country_code' => $customerDTO->phone_country_code,
                'phone'              => $customerDTO->phone,
            ]
        );

        $customer->update(['last_booking_at' => now()]);

        $data['customer_model'] = $customer;

        return $next($data);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Services\Interfaces;

interface IRegistrationRequestService
{
    /**
     * Store a new registration request.
     *
     * @param array $data
     * @return \App\Models\RegistrationRequest
     */
    public function storeRequest(array $data);
}

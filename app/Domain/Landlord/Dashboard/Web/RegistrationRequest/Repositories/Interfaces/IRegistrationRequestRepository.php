<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Repositories\Interfaces;

interface IRegistrationRequestRepository
{
    public function updateWhere(array $data, array $conditions = []);
}

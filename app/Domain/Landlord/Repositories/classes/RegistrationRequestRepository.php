<?php

declare(strict_types = 1)
;

namespace App\Domain\Landlord\Repositories\Classes;

use App\Domain\Landlord\Repositories\Interfaces\IRegistrationRequestRepository;
use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Models\RegistrationRequest;

class RegistrationRequestRepository extends AbstractRepository implements IRegistrationRequestRepository

{
}

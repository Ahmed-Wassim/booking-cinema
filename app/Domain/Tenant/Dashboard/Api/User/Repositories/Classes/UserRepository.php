<?php

declare(strict_types = 1)
;

namespace App\Domain\Tenant\Dashboard\Api\User\Repositories\Classes;

use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Domain\Tenant\Dashboard\Api\User\Repositories\Interfaces\IUserRepository;
use App\Models\Tenant\User;

class UserRepository extends AbstractRepository implements IUserRepository
{
}

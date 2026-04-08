<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\ActivityLog\Services\Interfaces;

interface IActivityLogService
{
    public function index(): mixed;
}

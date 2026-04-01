<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\ActivityLog\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\ActivityLog\Repositories\Interfaces\IActivityLogRepository;
use App\Domain\Tenant\Dashboard\Api\ActivityLog\Services\Interfaces\IActivityLogService;

class ActivityLogService implements IActivityLogService
{
    public function __construct(protected IActivityLogRepository $activityLogRepository) {}

    public function index(): mixed
    {
        $conditions = [];
        
        if (request()->filled('event')) {
            $conditions['event'] = request('event');
        }

        if (request()->filled('user_id')) {
            $conditions['causer_id'] = request('user_id');
        }

        // Mapping from/to (User request) to start_date/end_date (Trait requirement)
        if (request()->filled('from')) {
            request()->merge(['start_date' => request('from')]);
        }
        if (request()->filled('to')) {
            request()->merge(['end_date' => request('to')]);
        }

        return $this->activityLogRepository->retrieve(
            conditions: $conditions,
            relations: ['causer']
        );
    }
}

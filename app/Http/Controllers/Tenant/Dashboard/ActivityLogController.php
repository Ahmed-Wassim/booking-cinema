<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\ActivityLogIndexRequest;
use App\Domain\Tenant\Dashboard\Api\ActivityLog\Services\Interfaces\IActivityLogService;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    public function __construct(protected IActivityLogService $activityLogService) {}

    /**
     * Retrieve a paginated list of activity logs with filtering.
     *
     * @param ActivityLogIndexRequest $request
     * @return JsonResponse
     */
    public function index(ActivityLogIndexRequest $request): JsonResponse
    {
        $logs = $this->activityLogService->index();

        return response()->json($logs);
    }
}

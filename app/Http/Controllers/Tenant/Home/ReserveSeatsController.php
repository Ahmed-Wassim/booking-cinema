<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces\IShowtimeService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Home\ReserveSeatsRequest;
use Illuminate\Http\JsonResponse;

class ReserveSeatsController extends Controller
{
    public function __construct(
        protected IShowtimeService $showtimeService
    ) {}

    /**
     * POST /api/reserve-seats
     * Delegate to the existing ShowtimeService::reserveSeats() which handles
     * seat availability checks, locking and 10-minute reservation windows.
     */
    public function store(ReserveSeatsRequest $request): JsonResponse
    {
        try {
            $result = $this->showtimeService->reserveSeats(
                showtimeId: $request->validated('showtime_id'),
                seatIds:    $request->validated('seat_ids'),
            );

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

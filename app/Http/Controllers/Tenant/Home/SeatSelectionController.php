<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Home\Seat\Services\Interfaces\IHomeSeatService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\Home\ShowtimeSeatResource;

class SeatSelectionController extends Controller
{
    public function __construct(
        protected IHomeSeatService $homeSeatService
    ) {}

    /**
     * GET /showtimes/{id}/seats  (web/JSON)
     * Return the seat map page data for a showtime.
     */
    public function show(int $id)
    {
        $seats = $this->homeSeatService->getSeatsForShowtime($id);

        return ShowtimeSeatResource::collection($seats);
    }

    /**
     * GET /api/showtimes/{id}/seats  (API)
     * Return the seat map data as JSON for the frontend seat picker.
     */
    public function seats(int $id)
    {
        $seats = $this->homeSeatService->getSeatsForShowtime($id);

        return ShowtimeSeatResource::collection($seats);
    }
}

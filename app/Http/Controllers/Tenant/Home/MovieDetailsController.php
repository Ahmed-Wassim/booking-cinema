<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Home\Movie\Services\Interfaces\IHomeMovieService;
use App\Domain\Tenant\Home\Showtime\Services\Interfaces\IHomeShowtimeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\Home\PublicMovieResource;

class MovieDetailsController extends Controller
{
    public function __construct(
        protected IHomeMovieService    $homeMovieService,
        protected IHomeShowtimeService $homeShowtimeService
    ) {}

    /**
     * GET /movies/{id}
     * Return movie details + showtimes grouped by branch → date.
     */
    public function show(int $id)
    {
        $movie     = $this->homeMovieService->getMovieById($id);
        $showtimes = $this->homeShowtimeService->getShowtimesForMovie($id);

        return (new PublicMovieResource($movie))
            ->additional([
                'showtimes' => $showtimes,
            ]);
    }
}

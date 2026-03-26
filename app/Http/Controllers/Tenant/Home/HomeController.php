<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Home;

use App\Domain\Tenant\Home\Movie\Services\Interfaces\IHomeMovieService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\Home\PublicMovieResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeController extends Controller
{
    public function __construct(
        protected IHomeMovieService $homeMovieService
    ) {}

    /**
     * GET /
     * List all now-playing movies for the tenant.
     */
    public function index(): AnonymousResourceCollection
    {
        $movies = $this->homeMovieService->listNowPlaying();

        return PublicMovieResource::collection($movies);
    }
}

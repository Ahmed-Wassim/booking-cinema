<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Movie\DTO\MovieDTO;
use App\Domain\Tenant\Dashboard\Api\Movie\Services\Interfaces\IMovieService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreMovieRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateMovieRequest;
use App\Http\Resources\Tenant\Dashboard\Api\LandlordMovieResource;
use App\Http\Resources\Tenant\Dashboard\Api\MovieResource;
use App\Models\Tenant\Movie;

class MovieController extends Controller
{
    public function __construct(protected IMovieService $movieService
    ) {}

    public function index()
    {
        $movies = $this->movieService->listAllMovies();

        return MovieResource::collection($movies);
    }

    public function store(StoreMovieRequest $request)
    {
        try {
            $data = (array) MovieDTO::fromRequest($request->validated());
            $tenantMovie = $this->movieService->addMovieToTenant($data);

            return (new MovieResource($tenantMovie))
                ->additional(['message' => 'Movie added to tenant successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function landlordMovies()
    {
        $movies = $this->movieService->getLandlordMovies();

        return LandlordMovieResource::collection($movies);
    }

    public function show(string $id)
    {
        $movie = Movie::findOrFail($id);

        $landlordDetails = $movie->landlordMovie();

        return (new MovieResource($movie))
            ->additional([
                'landlord_details' => $landlordDetails,
            ]);
    }

    public function update(UpdateMovieRequest $request, string $id)
    {
        $data = (array) MovieDTO::fromRequest($request->validated());
        $movie = $this->movieService->updateMovie($id, $data);

        return (new MovieResource($movie))
            ->additional(['message' => 'Movie updated successfully']);
    }

    public function destroy(string $id)
    {
        $this->movieService->deleteMovie($id);

        return response()->json(null, 204);
    }
}

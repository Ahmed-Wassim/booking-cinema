<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces\IShowtimeService;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Showtime;
use Illuminate\Http\Request;
use App\Http\Requests\Tenant\Dashboard\Api\StoreShowtimeRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateShowtimeRequest;
use App\Http\Resources\Tenant\Dashboard\Api\ShowtimeResource;
use App\Domain\Tenant\Dashboard\Api\Showtime\DTO\ShowtimeDTO;

class ShowtimeController extends Controller
{
    public function __construct(
        protected IShowtimeService $showtimeService
    ) {}

    public function index()
    {
        // Scenario 2: Showtime List Page
        // You show: Movie title, Poster, Time, Hall
        // Since the prompt explicitly says: $showtimes = Showtime::with('tenantMovie')->get(); 
        // We're using relationships named 'movie'.
        
        $showtimes = $this->showtimeService->listAllShowtimes();

        return ShowtimeResource::collection($showtimes);
    }

    public function store(StoreShowtimeRequest $request)
    {
        try {
            $data = (array) ShowtimeDTO::fromRequest($request->validated());
            $showtime = $this->showtimeService->createShowtime($data);

            return (new ShowtimeResource($showtime))
                ->additional(['message' => 'Showtime created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(string $id)
    {
        $showtime = Showtime::with(['movie', 'hall', 'priceTier'])->findOrFail($id);
        
        return new ShowtimeResource($showtime);
    }

    public function update(UpdateShowtimeRequest $request, string $id)
    {
        $data = (array) ShowtimeDTO::fromRequest($request->validated());
        $showtime = $this->showtimeService->updateShowtime($id, $data);

        return (new ShowtimeResource($showtime))
            ->additional(['message' => 'Showtime updated successfully']);
    }

    public function destroy(string $id)
    {
        $this->showtimeService->deleteShowtime($id);
        
        return response()->json(null, 204);
    }
}

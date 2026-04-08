<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Showtime\DTO\ShowtimeDTO;
use App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces\IShowtimeService;
use App\Events\ShowtimeChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreShowtimeRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateShowtimeRequest;
use App\Http\Resources\Tenant\Dashboard\Api\ShowtimeResource;
use App\Models\Tenant\Showtime;

class ShowtimeController extends Controller
{
    public function __construct(
        protected IShowtimeService $showtimeService
    ) {
    }

    public function index()
    {
        $showtimes = $this->showtimeService->listAllShowtimes();

        return ShowtimeResource::collection($showtimes);
    }

    public function store(StoreShowtimeRequest $request)
    {
        try {
            $data = (array) ShowtimeDTO::fromRequest($request->validated());
            $showtime = $this->showtimeService->createShowtime($data);
            $showtime->load('hall');

            event(new ShowtimeChanged(
                tenant('id'),
                $showtime->hall->branch_id,
                $showtime->movie_id
            ));

            return (new ShowtimeResource($showtime))
                ->additional(['message' => 'Showtime created successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
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
        $showtime->load('hall');

        event(new \App\Events\ShowtimeChanged(
            tenant('id'),
            $showtime->hall->branch_id,
            $showtime->movie_id
        ));

        return (new ShowtimeResource($showtime))
            ->additional(['message' => 'Showtime updated successfully']);
    }

    public function destroy(string $id)
    {
        $showtime = Showtime::with('hall')->findOrFail($id);
        $branchId = $showtime->hall->branch_id;
        $movieId = $showtime->movie_id;

        $this->showtimeService->deleteShowtime($id);

        event(new \App\Events\ShowtimeChanged(
            tenant('id'),
            $branchId,
            $movieId
        ));

        return response()->json(null, 204);
    }
}

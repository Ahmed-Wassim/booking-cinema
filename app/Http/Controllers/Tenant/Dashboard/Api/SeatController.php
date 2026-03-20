<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Seat\DTO\SeatDTO;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Interfaces\ISeatService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreSeatRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateSeatRequest;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    public function __construct(
        protected ISeatService $seatService
    ) {}

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return \App\Http\Resources\Tenant\Dashboard\Api\SeatResource::collection($this->seatService->listAllSeats());
    }

    public function store(StoreSeatRequest $request): \App\Http\Resources\Tenant\Dashboard\Api\SeatResource
    {
        $seat = $this->seatService->storeSeat((array) SeatDTO::fromRequest($request->validated()));
        return new \App\Http\Resources\Tenant\Dashboard\Api\SeatResource($seat);
    }

    public function show(string $id): \App\Http\Resources\Tenant\Dashboard\Api\SeatResource
    {
        return new \App\Http\Resources\Tenant\Dashboard\Api\SeatResource($this->seatService->editSeat($id));
    }

    public function update(UpdateSeatRequest $request, string $id): \App\Http\Resources\Tenant\Dashboard\Api\SeatResource
    {
        $seat = $this->seatService->updateSeat((array) SeatDTO::fromRequest($request->validated()), $id);
        return new \App\Http\Resources\Tenant\Dashboard\Api\SeatResource($seat);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->seatService->deleteSeat($id);
        return response()->json(null, 204);
    }
}

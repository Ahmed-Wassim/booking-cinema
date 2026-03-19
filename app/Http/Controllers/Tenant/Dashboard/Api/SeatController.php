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

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->seatService->listAllSeats()
        ]);
    }

    public function store(StoreSeatRequest $request): JsonResponse
    {
        $seat = $this->seatService->storeSeat((array) SeatDTO::fromRequest($request->validated()));
        return response()->json(['data' => $seat], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->seatService->editSeat($id)
        ]);
    }

    public function update(UpdateSeatRequest $request, string $id): JsonResponse
    {
        $seat = $this->seatService->updateSeat((array) SeatDTO::fromRequest($request->validated()), $id);
        return response()->json(['data' => $seat]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->seatService->deleteSeat($id);
        return response()->json(null, 204);
    }
}

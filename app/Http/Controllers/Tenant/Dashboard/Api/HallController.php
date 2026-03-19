<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Hall\DTO\HallDTO;
use App\Domain\Tenant\Dashboard\Api\Hall\Services\Interfaces\IHallService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreHallRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateHallRequest;
use Illuminate\Http\JsonResponse;

class HallController extends Controller
{
    public function __construct(
        protected IHallService $hallService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->hallService->listAllHalls()
        ]);
    }

    public function store(StoreHallRequest $request): JsonResponse
    {
        $hall = $this->hallService->storeHall((array) HallDTO::fromRequest($request->validated()));
        return response()->json(['data' => $hall], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->hallService->editHall($id)
        ]);
    }

    public function update(UpdateHallRequest $request, string $id): JsonResponse
    {
        $hall = $this->hallService->updateHall((array) HallDTO::fromRequest($request->validated()), $id);
        return response()->json(['data' => $hall]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->hallService->deleteHall($id);
        return response()->json(null, 204);
    }
}

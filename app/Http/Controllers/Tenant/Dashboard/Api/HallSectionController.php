<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\HallSection\DTO\HallSectionDTO;
use App\Domain\Tenant\Dashboard\Api\HallSection\Services\Interfaces\IHallSectionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreHallSectionRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateHallSectionRequest;
use Illuminate\Http\JsonResponse;

class HallSectionController extends Controller
{
    public function __construct(
        protected IHallSectionService $hallSectionService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->hallSectionService->listAllHallSections()
        ]);
    }

    public function store(StoreHallSectionRequest $request): JsonResponse
    {
        $hallSection = $this->hallSectionService->storeHallSection((array) HallSectionDTO::fromRequest($request->validated()));
        return response()->json(['data' => $hallSection], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->hallSectionService->editHallSection($id)
        ]);
    }

    public function update(UpdateHallSectionRequest $request, string $id): JsonResponse
    {
        $hallSection = $this->hallSectionService->updateHallSection((array) HallSectionDTO::fromRequest($request->validated()), $id);
        return response()->json(['data' => $hallSection]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->hallSectionService->deleteHallSection($id);
        return response()->json(null, 204);
    }
}

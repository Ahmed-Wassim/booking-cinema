<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\HallSection\DTO\HallSectionDTO;
use App\Domain\Tenant\Dashboard\Api\HallSection\Services\Interfaces\IHallSectionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreHallSectionRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateHallSectionRequest;
use App\Http\Resources\Tenant\Dashboard\Api\HallSectionResource;
use Illuminate\Http\JsonResponse;

class HallSectionController extends Controller
{
    public function __construct(
        protected IHallSectionService $hallSectionService
    ) {}

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return HallSectionResource::collection($this->hallSectionService->listAllHallSections());
    }

    public function store(StoreHallSectionRequest $request): HallSectionResource
    {
        $hallSection = $this->hallSectionService->storeHallSection((array) HallSectionDTO::fromRequest($request->validated()));

        return new HallSectionResource($hallSection);
    }

    public function show(string $id): HallSectionResource
    {
        return new HallSectionResource($this->hallSectionService->editHallSection($id));
    }

    public function update(UpdateHallSectionRequest $request, string $id): HallSectionResource
    {
        $hallSection = $this->hallSectionService->updateHallSection((array) HallSectionDTO::fromRequest($request->validated()), $id);

        return new HallSectionResource($hallSection);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->hallSectionService->deleteHallSection($id);

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Hall\DTO\HallDTO;
use App\Domain\Tenant\Dashboard\Api\Hall\Services\Interfaces\IHallService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreHallRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateHallRequest;
use App\Http\Resources\Tenant\Dashboard\Api\HallResource;
use Illuminate\Http\JsonResponse;

class HallController extends Controller
{
    public function __construct(
        protected IHallService $hallService
    ) {}

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return HallResource::collection($this->hallService->listAllHalls());
    }

    public function store(StoreHallRequest $request): HallResource
    {
        $hall = $this->hallService->storeHall((array) HallDTO::fromRequest($request->validated()));

        return new HallResource($hall);
    }

    public function show(string $id): HallResource
    {
        return new HallResource($this->hallService->editHall($id));
    }

    public function update(UpdateHallRequest $request, string $id): HallResource
    {
        $hall = $this->hallService->updateHall((array) HallDTO::fromRequest($request->validated()), $id);

        return new HallResource($hall);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->hallService->deleteHall($id);

        return response()->json(null, 204);
    }
}

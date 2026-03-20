<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\PriceTier\DTO\PriceTierDTO;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Services\Interfaces\IPriceTierService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StorePriceTierRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdatePriceTierRequest;
use Illuminate\Http\JsonResponse;

class PriceTierController extends Controller
{
    public function __construct(
        protected IPriceTierService $priceTierService
    ) {}

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return \App\Http\Resources\Tenant\Dashboard\Api\PriceTierResource::collection($this->priceTierService->listAllPriceTiers());
    }

    public function store(StorePriceTierRequest $request): \App\Http\Resources\Tenant\Dashboard\Api\PriceTierResource
    {
        $priceTier = $this->priceTierService->storePriceTier((array) PriceTierDTO::fromRequest($request->validated()));
        return new \App\Http\Resources\Tenant\Dashboard\Api\PriceTierResource($priceTier);
    }

    public function show(string $id): \App\Http\Resources\Tenant\Dashboard\Api\PriceTierResource
    {
        return new \App\Http\Resources\Tenant\Dashboard\Api\PriceTierResource($this->priceTierService->editPriceTier($id));
    }

    public function update(UpdatePriceTierRequest $request, string $id): \App\Http\Resources\Tenant\Dashboard\Api\PriceTierResource
    {
        $priceTier = $this->priceTierService->updatePriceTier((array) PriceTierDTO::fromRequest($request->validated()), $id);
        return new \App\Http\Resources\Tenant\Dashboard\Api\PriceTierResource($priceTier);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->priceTierService->deletePriceTier($id);
        return response()->json(null, 204);
    }
}

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

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->priceTierService->listAllPriceTiers()
        ]);
    }

    public function store(StorePriceTierRequest $request): JsonResponse
    {
        $priceTier = $this->priceTierService->storePriceTier((array) PriceTierDTO::fromRequest($request->validated()));
        return response()->json(['data' => $priceTier], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->priceTierService->editPriceTier($id)
        ]);
    }

    public function update(UpdatePriceTierRequest $request, string $id): JsonResponse
    {
        $priceTier = $this->priceTierService->updatePriceTier((array) PriceTierDTO::fromRequest($request->validated()), $id);
        return response()->json(['data' => $priceTier]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->priceTierService->deletePriceTier($id);
        return response()->json(null, 204);
    }
}

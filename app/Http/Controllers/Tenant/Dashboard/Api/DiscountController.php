<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Discount\DTO\DiscountDTO;
use App\Domain\Tenant\Dashboard\Api\Discount\Services\Interfaces\IDiscountService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreDiscountRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateDiscountRequest;
use App\Http\Resources\Tenant\Dashboard\Api\DiscountResource;
use Illuminate\Http\JsonResponse;

class DiscountController extends Controller
{
    public function __construct(
        protected IDiscountService $discountService
    ) {}

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DiscountResource::collection($this->discountService->listAllDiscounts());
    }

    public function store(StoreDiscountRequest $request): DiscountResource
    {
        $discount = $this->discountService->storeDiscount((array) DiscountDTO::fromRequest($request->validated()));

        return new DiscountResource($discount);
    }

    public function show(string $id): DiscountResource
    {
        return new DiscountResource($this->discountService->editDiscount($id));
    }

    public function update(UpdateDiscountRequest $request, string $id): DiscountResource
    {
        $discount = $this->discountService->updateDiscount((array) DiscountDTO::fromRequest($request->validated()), $id);

        return new DiscountResource($discount);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->discountService->deleteDiscount($id);

        return response()->json(null, 204);
    }
}

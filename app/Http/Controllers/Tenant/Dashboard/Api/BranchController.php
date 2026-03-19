<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Branch\DTO\BranchDTO;
use App\Domain\Tenant\Dashboard\Api\Branch\Services\Interfaces\IBranchService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreBranchRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateBranchRequest;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function __construct(
        protected IBranchService $branchService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->branchService->listAllBranches()
        ]);
    }

    public function store(StoreBranchRequest $request): JsonResponse
    {
        $branch = $this->branchService->storeBranch((array) BranchDTO::fromRequest($request->validated()));
        return response()->json(['data' => $branch], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->branchService->editBranch($id)
        ]);
    }

    public function update(UpdateBranchRequest $request, string $id): JsonResponse
    {
        $branch = $this->branchService->updateBranch((array) BranchDTO::fromRequest($request->validated()), $id);
        return response()->json(['data' => $branch]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->branchService->deleteBranch($id);
        return response()->json(null, 204);
    }
}

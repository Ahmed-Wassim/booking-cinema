<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Branch\DTO\BranchDTO;
use App\Domain\Tenant\Dashboard\Api\Branch\Services\Interfaces\IBranchService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\StoreBranchRequest;
use App\Http\Requests\Tenant\Dashboard\Api\UpdateBranchRequest;
use App\Http\Resources\Tenant\Dashboard\Api\BranchResource;

class BranchController extends Controller
{
    public function __construct(
        protected IBranchService $branchService
    ) {}

    public function index()
    {
        return BranchResource::collection($this->branchService->listAllBranches());
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->branchService->storeBranch((array) BranchDTO::fromRequest($request->validated()));
        return new BranchResource($branch);
    }

    public function show(string $id)
    {
        return new BranchResource($this->branchService->editBranch($id));
    }

    public function update(UpdateBranchRequest $request, string $id)
    {
        $branch = $this->branchService->updateBranch((array) BranchDTO::fromRequest($request->validated()), $id);
        return new BranchResource($branch);
    }

    public function destroy(string $id)
    {
        $this->branchService->deleteBranch($id);
        return response()->json(null, 204);
    }
}

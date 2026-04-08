<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Role\DTO\RoleDTO;
use App\Domain\Tenant\Dashboard\Api\Role\Services\Interfaces\IRoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\Role\StoreRoleRequest;
use App\Http\Requests\Tenant\Dashboard\Api\Role\UpdateRoleRequest;
use App\Http\Resources\Tenant\Dashboard\Api\RoleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoleController extends Controller
{
    public function __construct(
        protected IRoleService $roleService
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection($this->roleService->listAllRoles());
    }

    public function permissions(): JsonResponse
    {
        return response()->json($this->roleService->getPermissionsGrouped());
    }

    public function store(StoreRoleRequest $request): RoleResource
    {
        $role = $this->roleService->storeRole((array) RoleDTO::fromRequest($request->validated()));
        return new RoleResource($role);
    }

    public function show(string $id): RoleResource
    {
        return new RoleResource($this->roleService->editRole($id));
    }

    public function update(UpdateRoleRequest $request, string $id): RoleResource
    {
        $role = $this->roleService->updateRole((array) RoleDTO::fromRequest($request->validated()), $id);
        return new RoleResource($role);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->roleService->deleteRole($id);
        return response()->json(null, 204);
    }
}

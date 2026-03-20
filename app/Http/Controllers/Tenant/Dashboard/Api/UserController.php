<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\User\DTO\UserDTO;
use App\Domain\Tenant\Dashboard\Api\User\Services\Interfaces\IUserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\User\StoreUserRequest;
use App\Http\Requests\Tenant\Dashboard\Api\User\UpdateUserRequest;
use App\Http\Resources\Tenant\Dashboard\Api\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        protected IUserService $userService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection($this->userService->listAllUsers());
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->storeUser((array) UserDTO::fromRequest($request->validated()));
        return new UserResource($user);
    }

    public function show(string $id): UserResource
    {
        return new UserResource($this->userService->editUser($id));
    }

    public function update(UpdateUserRequest $request, string $id): UserResource
    {
        $user = $this->userService->updateUser((array) UserDTO::fromRequest($request->validated()), $id);
        return new UserResource($user);
    }

    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        $this->userService->deleteUser($id);
        return response()->json(null, 204);
    }
}

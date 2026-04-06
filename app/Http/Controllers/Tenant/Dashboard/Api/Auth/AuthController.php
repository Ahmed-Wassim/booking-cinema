<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api\Auth;

use App\Domain\Tenant\Dashboard\Api\User\DTO\UserDTO;
use App\Domain\Tenant\Dashboard\Api\User\Services\Interfaces\IUserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Dashboard\Api\Auth\LoginRequest;
use App\Http\Requests\Tenant\Dashboard\Api\Auth\RegisterRequest;
use App\Http\Resources\Tenant\Dashboard\Api\UserResource;
use App\Models\Tenant\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function __construct(
        protected IUserService $userService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user  = $this->userService->storeUser((array) UserDTO::fromRequest($request->validated()));
        /** @var JWTGuard $guard */
        $guard = auth('tenant');
        $token = $guard->login($user);

        return response()->json([
            'user'      => new UserResource($user),
            'token'     => $token,
            'tenant_id' => tenant('id'),
            'abilities' => $user->getAllPermissions()->pluck('name'),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => __('tenant.Invalid credentials'),
            ], 401);
        }

        /** @var JWTGuard $guard */
        $guard = auth('tenant');
        $token = $guard->login($user);

        return response()->json([
            'user'      => new UserResource($user),
            'token'     => $token,
            'tenant_id' => tenant('id'),
            'abilities' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function logout(): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = auth('tenant');
        $guard->logout();

        return response()->json([
            'message' => __('tenant.Logged out successfully'),
        ]);
    }
}

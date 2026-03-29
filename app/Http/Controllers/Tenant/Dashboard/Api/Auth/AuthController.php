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

class AuthController extends Controller
{
    public function __construct(
        protected IUserService $userService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userService->storeUser((array) UserDTO::fromRequest($request->validated()));
        $token = $user->createToken($request->device_name ?? 'tenant_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'abilities' => $user->getAllPermissions()->pluck('name'),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken($request->device_name ?? 'tenant_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            'abilities' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}

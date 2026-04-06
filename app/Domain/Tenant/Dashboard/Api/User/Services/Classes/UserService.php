<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\User\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\User\Repositories\Interfaces\IUserRepository;
use App\Domain\Tenant\Dashboard\Api\User\Services\Interfaces\IUserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService implements IUserService
{
    public function __construct(private IUserRepository $userRepository)
    {
    }

    public function listAllUsers(): LengthAwarePaginator
    {
        return $this->userRepository->retrieve(relations: ['roles']);
    }

    public function storeUser(array $data): Model
    {
        try {
            DB::beginTransaction();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->userRepository->create($data);

            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }
    }

    public function editUser(string $id): Model
    {
        return $this->userRepository->findOrFail((int) $id)->load('roles');
    }

    public function updateUser(array $data, string $id): Model
    {
        try {
            DB::beginTransaction();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user = $this->userRepository->update($data, ['id' => $id]);

            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }

    public function deleteUser(string $id): void
    {
        try {
            DB::beginTransaction();
            $this->userRepository->delete(['id' => $id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Failed to delete user: ' . $e->getMessage()]);
        }
    }
}

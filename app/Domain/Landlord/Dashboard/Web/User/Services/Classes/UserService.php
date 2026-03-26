<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Dashboard\Web\User\Services\Classes;

use App\Domain\Landlord\Dashboard\Web\User\Repositories\Interfaces\IUserRepository;
use App\Domain\Landlord\Dashboard\Web\User\Services\Interfaces\IUserService;

class UserService implements IUserService
{
    public function __construct(
        protected IUserRepository $userRepository
    ) {
    }

    public function listAllUsers()
    {
        return $this->userRepository->retrieve();
    }

    public function storeUser(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function editUser(int $id)
    {
    }

    public function updateUser(array $data, int $id)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $this->userRepository->update($data, ['id' => $id]);
    }

    public function deleteUser(int $id)
    {
        return $this->userRepository->delete(['id' => $id]);
    }
}

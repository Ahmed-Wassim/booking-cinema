<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Dashboard\Web\User\Services\Interfaces;

interface IUserService
{
    public function listAllUsers();

    public function storeUser(array $data);

    public function editUser(int $id);

    public function updateUser(array $data, int $id);

    public function deleteUser(int $id);
}

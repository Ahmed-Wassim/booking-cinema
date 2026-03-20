<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\User\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IUserService
{
    public function listAllUsers(): Collection;

    public function storeUser(array $data): Model;

    public function editUser(string $id): Model;

    public function updateUser(array $data, string $id): Model;

    public function deleteUser(string $id): void;
}

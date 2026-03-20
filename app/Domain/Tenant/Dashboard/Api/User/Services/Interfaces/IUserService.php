<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\User\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IUserService
{
    public function listAllUsers(): LengthAwarePaginator;

    public function storeUser(array $data): Model;

    public function editUser(string $id): Model;

    public function updateUser(array $data, string $id): Model;

    public function deleteUser(string $id): void;
}

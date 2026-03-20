<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Hall\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IHallService
{
    public function listAllHalls(): LengthAwarePaginator;

    public function storeHall(array $data): Model;

    public function editHall(string|int $id): Model;

    public function updateHall(array $data, string|int $id): Model;

    public function deleteHall(string|int $id): bool;
}

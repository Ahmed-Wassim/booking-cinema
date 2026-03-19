<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Hall\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IHallService
{
    public function listAllHalls(): Collection|array;
    public function storeHall(array $data): Model;
    public function editHall(string|int $id): Model;
    public function updateHall(array $data, string|int $id): Model;
    public function deleteHall(string|int $id): bool;
}

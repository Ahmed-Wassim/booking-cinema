<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\HallSection\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IHallSectionService
{
    public function listAllHallSections(): LengthAwarePaginator;

    public function storeHallSection(array $data): Model;

    public function editHallSection(string|int $id): Model;

    public function updateHallSection(array $data, string|int $id): Model;

    public function deleteHallSection(string|int $id): bool;
}

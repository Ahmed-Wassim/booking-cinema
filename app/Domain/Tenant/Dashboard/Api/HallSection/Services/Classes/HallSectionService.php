<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\HallSection\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\HallSection\Repositories\Interfaces\IHallSectionRepository;
use App\Domain\Tenant\Dashboard\Api\HallSection\Services\Interfaces\IHallSectionService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class HallSectionService implements IHallSectionService
{
    public function __construct(
        protected IHallSectionRepository $hallSectionRepository
    ) {}

    public function listAllHallSections(): LengthAwarePaginator
    {
        return $this->hallSectionRepository->retrieve();
    }

    public function storeHallSection(array $data): Model
    {
        return $this->hallSectionRepository->create($data);
    }

    public function editHallSection(string|int $id): Model
    {
        return $this->hallSectionRepository->findOrFail((int) $id);
    }

    public function updateHallSection(array $data, string|int $id): Model
    {
        return $this->hallSectionRepository->update($data, ['id' => $id]);
    }

    public function deleteHallSection(string|int $id): bool
    {
        $this->hallSectionRepository->delete(['id' => $id]);

        return true;
    }
}

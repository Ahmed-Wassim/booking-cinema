<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\HallSection\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\HallSection\Repositories\Interfaces\IHallSectionRepository;
use App\Domain\Tenant\Dashboard\Api\HallSection\Services\Interfaces\IHallSectionService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HallSectionService implements IHallSectionService
{
    public function __construct(
        protected IHallSectionRepository $hallSectionRepository
    ) {}

    public function listAllHallSections(): Collection|array
    {
        return $this->hallSectionRepository->listAllBy(relations: ['hall']);
    }

    public function storeHallSection(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            return $this->hallSectionRepository->create($data);
        });
    }

    public function editHallSection(string|int $id): Model
    {
        return $this->hallSectionRepository->findOrFail((int)$id);
    }

    public function updateHallSection(array $data, string|int $id): Model
    {
        return DB::transaction(function () use ($data, $id) {
            return $this->hallSectionRepository->update($data, ['id' => $id]);
        });
    }

    public function deleteHallSection(string|int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $this->hallSectionRepository->delete(['id' => $id]);
            return true;
        });
    }
}

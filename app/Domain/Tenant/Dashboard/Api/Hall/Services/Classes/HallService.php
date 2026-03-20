<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Hall\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Hall\Repositories\Interfaces\IHallRepository;
use App\Domain\Tenant\Dashboard\Api\Hall\Services\Interfaces\IHallService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class HallService implements IHallService
{
    public function __construct(
        protected IHallRepository $hallRepository
    ) {}

    public function listAllHalls(): Collection|array
    {
        return $this->hallRepository->listAllBy(relations: ['branch']);
    }

    public function storeHall(array $data): Model
    {
        return $this->hallRepository->create($data);
    }

    public function editHall(string|int $id): Model
    {
        return $this->hallRepository->findOrFail((int) $id);
    }

    public function updateHall(array $data, string|int $id): Model
    {
        return $this->hallRepository->update($data, ['id' => $id]);
    }

    public function deleteHall(string|int $id): bool
    {
        $this->hallRepository->delete(['id' => $id]);

        return true;
    }
}

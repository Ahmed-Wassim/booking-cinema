<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Seat\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Seat\Repositories\Interfaces\ISeatRepository;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Interfaces\ISeatService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SeatService implements ISeatService
{
    public function __construct(
        protected ISeatRepository $seatRepository
    ) {}

    public function listAllSeats(): Collection|array
    {
        return $this->seatRepository->listAllBy(relations: ['hall', 'section', 'priceTier']);
    }

    public function storeSeat(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            return $this->seatRepository->create($data);
        });
    }

    public function editSeat(string|int $id): Model
    {
        return $this->seatRepository->findOrFail((int)$id);
    }

    public function updateSeat(array $data, string|int $id): Model
    {
        return DB::transaction(function () use ($data, $id) {
            return $this->seatRepository->update($data, ['id' => $id]);
        });
    }

    public function deleteSeat(string|int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $this->seatRepository->delete(['id' => $id]);
            return true;
        });
    }
}

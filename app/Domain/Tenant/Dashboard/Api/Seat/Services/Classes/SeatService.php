<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Seat\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Seat\Repositories\Interfaces\ISeatRepository;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Interfaces\ISeatService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class SeatService implements ISeatService
{
    public function __construct(
        protected ISeatRepository $seatRepository
    ) {}

    public function listAllSeats()
    {
        return $this->seatRepository->listAllBy();
    }

    public function storeSeat(array $data): Model
    {
        return $this->seatRepository->create($data);
    }

    public function bulkStoreSeats(array $data): bool
    {
        return $this->seatRepository->createMany($data);
    }

    public function editSeat(string|int $id): Model
    {
        return $this->seatRepository->findOrFail((int) $id);
    }

    public function updateSeat(array $data, string|int $id): Model
    {
        return $this->seatRepository->update($data, ['id' => $id]);
    }

    public function deleteSeat(string|int $id): bool
    {
        $this->seatRepository->delete(['id' => $id]);

        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Seat\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Seat\Repositories\Interfaces\ISeatRepository;
use App\Domain\Tenant\Dashboard\Api\Seat\Services\Interfaces\ISeatService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function bulkStoreSeats(array $data, int $hallId): bool
    {
        DB::beginTransaction();

        $updates = [];
        $creates = [];
        $incomingIds = [];

        foreach ($data as $seat) {
            $seat['hall_id'] = $hallId;

            if (! empty($seat['id'])) {
                $incomingIds[] = $seat['id'];
                $updates[] = $seat;
            } else {
                unset($seat['id']);
                $creates[] = $seat;
            }
        }

        DB::table('seats')
            ->where('hall_id', $hallId)
            ->when(! empty($incomingIds), function ($q) use ($incomingIds) {
                $q->whereNotIn('id', $incomingIds);
            })
            ->delete();

        if (! empty($updates)) {
            DB::table('seats')->upsert(
                $updates,
                ['id'],
                ['price_tier_id', 'row', 'number', 'pos_x', 'pos_y', 'hall_id']
            );
        }

        if (! empty($creates)) {
            DB::table('seats')->insert($creates);
        }
        DB::commit();

        return true;
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

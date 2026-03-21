<?php

namespace App\Domain\Tenant\Dashboard\Api\Showtime\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Hall\Repositories\Interfaces\IHallRepository;
use App\Domain\Tenant\Dashboard\Api\Movie\Repositories\Interfaces\IMovieRepository;
use App\Domain\Tenant\Dashboard\Api\Showtime\Repositories\Interfaces\IShowtimeRepository;
use App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces\IShowtimeService;
use App\Domain\Tenant\Dashboard\Api\ShowtimeSeat\Repositories\Interfaces\IShowtimeSeatRepository;
use App\Enums\Tenant\ShowtimeSeatStatus;
use App\Models\Tenant\Movie;
use App\Models\Tenant\Showtime;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ShowtimeService implements IShowtimeService
{
    public function __construct(
        protected IShowtimeRepository $showtimeRepository,
        protected IShowtimeSeatRepository $showtimeSeatRepository,
        protected IMovieRepository $movieRepository,
        protected IHallRepository $hallRepository
    ) {}

    public function createShowtime(array $data): Showtime
    {
        $tenantMovieId = $data['movie_id'];
        $hallId = $data['hall_id'];
        $startTime = $data['start_time'];
        $priceTierId = $data['price_tier_id'] ?? null;

        $tenantMovie = $this->movieRepository->find($tenantMovieId);

        if (! $tenantMovie) {
            throw new Exception('Movie not found in the tenant catalog.');
        }

        // Parse start time to Carbon if it's a string
        $start = Carbon::parse($startTime);

        // Calculate end time
        // The End Time = Start Time + Runtime of the movie (from the snapshot)
        $runtime = $tenantMovie->runtime ?? 120; // fallback to 120 min if unknown
        $endTime = $start->copy()->addMinutes($runtime);

        DB::beginTransaction();
        try {
            $showtime = $this->showtimeRepository->create([
                'movie_id' => $tenantMovie->id,
                'hall_id' => $hallId,
                'start_time' => $start,
                'end_time' => $endTime,
                'price_tier_id' => $priceTierId,
                'status' => 'active',
            ]);

            $hall = $this->hallRepository->first(['id' => $hallId], ['seats']);
            if ($hall && $hall->seats->isNotEmpty()) {
                $seatsData = [];
                $now = now();

                foreach ($hall->seats as $seat) {
                    $seatsData[] = [
                        'showtime_id' => $showtime->id,
                        'seat_id' => $seat->id,
                        'status' => ShowtimeSeatStatus::AVAILABLE->value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                $this->showtimeSeatRepository->createMany($seatsData);
            }

            DB::commit();

            return $showtime;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function listAllShowtimes(): LengthAwarePaginator
    {
        return $this->showtimeRepository->retrieve();
    }

    public function updateShowtime(int $id, array $data): Showtime
    {
        $showtime = $this->showtimeRepository->findOrFail($id);

        if (isset($data['start_time'])) {
            $start = Carbon::parse($data['start_time']);
            // Recalculate end time based on the new start time
            $runtime = $showtime->movie->runtime ?? 120;
            $data['start_time'] = $start;
            $data['end_time'] = $start->copy()->addMinutes($runtime);
        }

        return $this->showtimeRepository->update($data, ['id' => $id]);
    }

    public function deleteShowtime(int $id): bool
    {
        $this->showtimeRepository->delete(['id' => $id]);

        return true;
    }

    public function reserveSeats(int $showtimeId, array $seatIds): array
    {
        DB::beginTransaction();
        try {
            $seats = $this->showtimeSeatRepository->prepareQuery(
                conditions: ['showtime_id' => $showtimeId]
            )->whereIn('seat_id', $seatIds)
                ->lockForUpdate()
                ->get();

            if ($seats->count() !== count($seatIds)) {
                throw new Exception('One or more selected seats do not exist for this showtime.');
            }

            foreach ($seats as $seat) {
                if ($seat->status !== ShowtimeSeatStatus::AVAILABLE->value) {
                    // Overwrite if reserved and expired
                    if ($seat->status === ShowtimeSeatStatus::RESERVED->value && $seat->reserved_until && $seat->reserved_until->isPast()) {
                        continue;
                    }
                    throw new Exception("Seat {$seat->seat_id} is already {$seat->status}.");
                }
            }

            $reservedUntil = now()->addMinutes(10);

            $this->showtimeSeatRepository->updateWhereIn(
                data: [
                    'status' => ShowtimeSeatStatus::RESERVED->value,
                    'reserved_until' => $reservedUntil,
                ],
                ids: $seatIds,
                selectedColumn: 'seat_id',
                conditions: ['showtime_id' => $showtimeId]
            );

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Seats reserved successfully.',
                'reserved_until' => $reservedUntil,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

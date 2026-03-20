<?php

namespace App\Domain\Tenant\Dashboard\Api\Showtime\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Showtime\Repositories\Interfaces\IShowtimeRepository;
use App\Domain\Tenant\Dashboard\Api\Showtime\Services\Interfaces\IShowtimeService;
use App\Models\Tenant\Movie;
use App\Models\Tenant\Showtime;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ShowtimeService implements IShowtimeService
{
    public function __construct(
        protected IShowtimeRepository $showtimeRepository
    ) {}

    public function createShowtime(array $data): Showtime
    {
        $tenantMovieId = $data['movie_id'];
        $hallId = $data['hall_id'];
        $startTime = $data['start_time'];
        $priceTierId = $data['price_tier_id'] ?? null;

        $tenantMovie = Movie::find($tenantMovieId);

        if (! $tenantMovie) {
            throw new Exception('Movie not found in the tenant catalog.');
        }

        // Parse start time to Carbon if it's a string
        $start = Carbon::parse($startTime);

        // Calculate end time
        // The End Time = Start Time + Runtime of the movie (from the snapshot)
        $runtime = $tenantMovie->runtime ?? 120; // fallback to 120 min if unknown
        $endTime = $start->copy()->addMinutes($runtime);

        return $this->showtimeRepository->create([
            'movie_id' => $tenantMovie->id,
            'hall_id' => $hallId,
            'start_time' => $start,
            'end_time' => $endTime,
            'price_tier_id' => $priceTierId,
            'status' => 'active',
        ]);
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
}

<?php

namespace App\Jobs;

use App\Domain\Landlord\MovieSync\Interfaces\IMovieSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncMoviesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(IMovieSyncService $movieSyncService): void
    {
        $start = microtime(true);

        $movieSyncService->syncBySupplierKey('tmdb');

        $duration = microtime(true) - $start;
        Log::channel('time')->info('SyncMoviesJob completed.', [
            'duration_seconds' => $duration,
        ]);
    }
}

<?php

namespace App\Listeners;

use App\Events\ShowtimeChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncShowtimeToGo implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ShowtimeChanged $event): void
    {
        $goServiceUrl = env('GO_SERVICE_URL', 'http://172.17.0.1:3003'); // Using Docker's default host bridge as a safe fallback if host.docker.internal fails

        try {
            $response = Http::post("{$goServiceUrl}/sync-showtime", [
                'tenant_id' => (string) $event->tenantId,
                'branch_id' => $event->branchId,
                'movie_id'  => $event->movieId,
            ]);

            if ($response->failed()) {
                Log::error('Failed to sync showtime to Go service', [
                    'tenant_id' => $event->tenantId,
                    'branch_id' => $event->branchId,
                    'movie_id'  => $event->movieId,
                    'status'    => $response->status(),
                    'body'      => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception while syncing showtime to Go service', [
                'tenant_id' => $event->tenantId,
                'branch_id' => $event->branchId,
                'movie_id'  => $event->movieId,
                'error'     => $e->getMessage(),
            ]);
        }
    }
}

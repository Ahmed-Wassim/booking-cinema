<?php

namespace App\Jobs;

use App\Enums\Tenant\ShowtimeSeatStatus;
use App\Models\Tenant\ShowtimeSeat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReleaseReservedSeatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $releasedCount = ShowtimeSeat::where('status', ShowtimeSeatStatus::RESERVED->value)
            ->where('reserved_until', '<', now())
            ->update([
                'status' => ShowtimeSeatStatus::AVAILABLE->value,
                'reserved_until' => null,
            ]);

        if ($releasedCount > 0) {
            Log::info("Released $releasedCount expired reserved seats.");
        }
    }
}

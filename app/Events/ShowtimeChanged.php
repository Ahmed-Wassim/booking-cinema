<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShowtimeChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int|string $tenantId,
        public int $branchId,
        public int $movieId
    ) {}
}

<?php

namespace App\Enums\Tenant;

enum ShowtimeSeatStatus: string
{
    case AVAILABLE = 'available';
    case RESERVED = 'reserved';
    case BOOKED = 'booked';
    case CANCELLED = 'cancelled';
}

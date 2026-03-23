<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
}

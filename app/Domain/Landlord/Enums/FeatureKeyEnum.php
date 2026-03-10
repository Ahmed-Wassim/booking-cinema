<?php

namespace App\Domain\Landlord\Enums;

enum FeatureKeyEnum: string
{
    case MAX_CINEMAS = 'max_cinemas';
    case MAX_HALLS = 'max_halls';
    case MAX_BOOKINGS = 'max_bookings';
    case UNLIMITED = 'unlimited';
}

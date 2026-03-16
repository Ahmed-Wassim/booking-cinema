<?php

namespace App\Domain\Landlord\Enums;

enum RegistrationRequestStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

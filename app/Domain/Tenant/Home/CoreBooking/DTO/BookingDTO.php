<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\CoreBooking\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class BookingDTO extends DataTransferObject
{
    public int   $showtime_id;
    public array $seat_ids;
    public ?int  $user_id;

    public static function fromRequest(array $data): self
    {
        return new self([
            'showtime_id' => (int) $data['showtime_id'],
            'seat_ids'    => (array) $data['seat_ids'],
            'user_id'     => isset($data['user_id']) ? (int) $data['user_id'] : null,
        ]);
    }
}

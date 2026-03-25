<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Seat\DTO;

class SeatsDTO
{
    /**
     * @var array
     */
    public array $seats;

    public function __construct(array $seats)
    {
        $this->seats = $seats;
    }

    public static function fromRequest(array $data): self
    {
        $now = now()->toDateTimeString();
        $mappedSeats = [];

        foreach ($data as $seatData) {
            $mapped = (array) SeatDTO::fromRequest($seatData);
            $mapped['created_at'] = $now;
            $mapped['updated_at'] = $now;
            $mappedSeats[] = $mapped;
        }

        return new self($mappedSeats);
    }

    public function toArray(): array
    {
        return $this->seats;
    }
}

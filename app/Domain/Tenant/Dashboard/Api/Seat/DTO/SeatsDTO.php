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
        $mappedSeats = [];

        foreach ($data as $seatData) {
            $mappedSeats[] = (array) SeatDTO::fromRequest($seatData);
        }

        return new self($mappedSeats);
    }

    public function toArray(): array
    {
        return $this->seats;
    }
}

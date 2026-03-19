<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Seat\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ISeatService
{
    public function listAllSeats(): Collection|array;
    public function storeSeat(array $data): Model;
    public function editSeat(string|int $id): Model;
    public function updateSeat(array $data, string|int $id): Model;
    public function deleteSeat(string|int $id): bool;
}

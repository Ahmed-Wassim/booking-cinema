<?php

namespace App\Domain\Landlord\Dashboard\Web\Plan\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IPlanService
{
    /**
     * @return Collection|array
     */
    public function listAllPlans(): Collection|array;

    /**
     * @param array $data
     * @return Model
     */
    public function storePlan(array $data): Model;

    /**
     * @param string|int $id
     * @return Model
     */
    public function editPlan(string|int $id): Model;

    /**
     * @param array $data
     * @param string|int $id
     * @return Model
     */
    public function updatePlan(array $data, string|int $id): Model;

    /**
     * @param string|int $id
     * @return bool
     */
    public function deletePlan(string|int $id): bool;
}

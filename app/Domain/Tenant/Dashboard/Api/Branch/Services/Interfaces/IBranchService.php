<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Branch\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IBranchService
{
    public function listAllBranches(): Collection|array;
    public function storeBranch(array $data): Model;
    public function editBranch(string|int $id): Model;
    public function updateBranch(array $data, string|int $id): Model;
    public function deleteBranch(string|int $id): bool;
}

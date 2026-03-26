<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Branch\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IBranchService
{
    public function listAllBranches(): LengthAwarePaginator;

    public function storeBranch(array $data): Model;

    public function editBranch(string|int $id): Model;

    public function updateBranch(array $data, string|int $id): Model;

    public function deleteBranch(string|int $id): bool;
}

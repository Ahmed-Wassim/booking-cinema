<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Branch\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Branch\Repositories\Interfaces\IBranchRepository;
use App\Domain\Tenant\Dashboard\Api\Branch\Services\Interfaces\IBranchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BranchService implements IBranchService
{
    public function __construct(
        protected IBranchRepository $branchRepository
    ) {}

    public function listAllBranches(): Collection|array
    {
        return $this->branchRepository->listAllBy();
    }

    public function storeBranch(array $data): Model
    {
        return $this->branchRepository->create($data);
    }

    public function editBranch(string|int $id): Model
    {
        return $this->branchRepository->findOrFail((int) $id);
    }

    public function updateBranch(array $data, string|int $id): Model
    {
        return $this->branchRepository->update($data, ['id' => $id]);
    }

    public function deleteBranch(string|int $id): bool
    {
        $this->branchRepository->delete(['id' => $id]);

        return true;
    }
}

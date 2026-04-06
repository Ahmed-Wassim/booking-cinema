<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Role\Services\Classes;

use App\Domain\Tenant\Dashboard\Api\Role\Services\Interfaces\IRoleService;
use App\Models\Tenant\Permission;
use App\Models\Tenant\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

readonly class RoleService implements IRoleService
{
    public function listAllRoles(): LengthAwarePaginator
    {
        return Role::query()
            ->withCount('permissions')
            ->paginate(request('paginate') ?? config('general_settings.pagination.value', env('PAGINATE_COUNT', 25)));
    }

    public function storeRole(array $data): Model
    {
        try {
            DB::beginTransaction();

            /** @var Role $role */
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => $data['guard_name'],
            ]);

            if (!empty($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Failed to create role: ' . $e->getMessage()]);
        }
    }

    public function editRole(string $id): Model
    {
        $role = Role::findOrFail((int) $id);
        $role->load('permissions');

        return $role;
    }

    public function updateRole(array $data, string $id): Model
    {
        try {
            DB::beginTransaction();

            $role = Role::findOrFail((int) $id);

            $role->update([
                'name' => $data['name'],
                'guard_name' => $data['guard_name'] ?? 'tenant',
            ]);

            if (isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Failed to update role: ' . $e->getMessage()]);
        }
    }

    public function deleteRole(string $id): void
    {
        $role = Role::findOrFail((int) $id);

        if ($role->users()->exists()) {
            throw ValidationException::withMessages(['error' => 'This role is assigned to users and cannot be deleted.']);
        }

        $role->delete();
    }

    public function getPermissionsGrouped(): \Illuminate\Support\Collection
    {
        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn($permission) => $this->permissionGroup($permission->name))
            ->map(fn($group) => $group->map(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'label' => $this->permissionLabel($permission->name),
            ]))->values();
    }

    private function permissionGroup(string $permission): string
    {
        if (str_contains($permission, '-')) {
            return ucfirst(explode('-', $permission)[0]);
        }

        return 'Other';
    }

    private function permissionLabel(string $permission): string
    {
        $parts = explode('-', $permission);

        if (count($parts) === 1) {
            return ucfirst($parts[0]);
        }

        $action = array_shift($parts);
        $object = implode(' ', array_map(fn($w) => ucfirst($w), $parts));

        return ucfirst($action) . ' ' . $object;
    }
}

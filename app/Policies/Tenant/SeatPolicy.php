<?php

namespace App\Policies\Tenant;

use App\Models\Tenant\User;
use App\Models\Tenant\Seat;

class SeatPolicy
{
    private const PERMISSION_BASE_NAME = 'seat-';
    private ?\App\Models\Tenant $currentTenant;

    public function __construct()
    {
        $this->currentTenant = tenant();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'view')
            && $this->currentTenant !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Seat $seat): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'view')
            && $this->currentTenant !== null;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'create')
            && $this->currentTenant !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Seat $seat): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'update')
            && $this->currentTenant !== null;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Seat $seat): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'delete')
            && $this->currentTenant !== null;
    }
}
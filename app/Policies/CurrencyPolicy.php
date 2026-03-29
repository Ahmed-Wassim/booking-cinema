<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Currency;

class CurrencyPolicy
{
    private const PERMISSION_BASE_NAME = 'currency-';

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Currency $currency): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Currency $currency): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Currency $currency): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'delete');
    }
}
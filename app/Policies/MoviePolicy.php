<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Movie;

class MoviePolicy
{
    private const PERMISSION_BASE_NAME = 'movie-';

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
    public function view(User $user, Movie $movie): bool
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
    public function update(User $user, Movie $movie): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Movie $movie): bool
    {
        return $user->hasPermissionTo(self::PERMISSION_BASE_NAME . 'delete');
    }
}
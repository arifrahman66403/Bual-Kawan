<?php

namespace App\Policies;

use App\Models\KisUser;

class KisUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(KisUser $kisUser): bool
    {
        return $kisUser->role === 'superadmin' || $kisUser->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(KisUser $kisUser, KisUser $model): bool
    {
        if ($kisUser->role === 'superadmin' || $kisUser->role === 'admin') {
            return true;
        }

        // operator can view only their own record
        return $kisUser->role === 'operator' && $kisUser->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(KisUser $kisUser): bool
    {
        return $kisUser->role === 'superadmin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(KisUser $kisUser, KisUser $model): bool
    {
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        if ($kisUser->role === 'admin') {
            return $model->role !== 'superadmin';
        }

        // operator can update only their own record
        return $kisUser->role === 'operator' && $kisUser->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(KisUser $kisUser, KisUser $model): bool
    {
        return $kisUser->role === 'superadmin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(KisUser $kisUser, KisUser $model): bool
    {
        return $kisUser->role === 'superadmin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(KisUser $kisUser, KisUser $model): bool
    {
        return $kisUser->role === 'superadmin';
    }
}

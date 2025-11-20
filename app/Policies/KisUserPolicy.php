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
        // superadmin dan admin biasa boleh membuat user
        return $kisUser->role === 'superadmin' || $kisUser->role === 'admin';
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
            // admin bisa mengupdate siapa saja kecuali superadmin
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
        // superadmin boleh delete semua.
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        // admin bisa menghapus kecuali superadmin dan tidak boleh menghapus diri sendiri
        if ($kisUser->role === 'admin') {
            return $model->role !== 'superadmin' && $kisUser->id !== $model->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(KisUser $kisUser, KisUser $model): bool
    {
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        if ($kisUser->role === 'admin') {
            return $model->role !== 'superadmin' && $kisUser->id !== $model->id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(KisUser $kisUser, KisUser $model): bool
    {
        if ($kisUser->role === 'superadmin') {
            return true;
        }

        if ($kisUser->role === 'admin') {
            return $model->role !== 'superadmin' && $kisUser->id !== $model->id;
        }

        return false;
    }
}

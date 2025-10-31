<?php

namespace App\Policies;

use App\Models\KisUser;
use Illuminate\Auth\Access\Response;

class KisUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(KisUser $kisUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(KisUser $kisUser, KisUser $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(KisUser $kisUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(KisUser $kisUser, KisUser $kisUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(KisUser $kisUser, KisUser $kisUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(KisUser $kisUser, KisUser $kisUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(KisUser $kisUser, KisUser $kisUser): bool
    {
        return false;
    }
}

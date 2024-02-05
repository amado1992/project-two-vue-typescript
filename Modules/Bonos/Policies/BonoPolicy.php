<?php

namespace Modules\Bonos\Policies;

use Modules\Bonos\Entities\Bono;
use Modules\Common\Permissions\BonoPermissions;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class BonoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(BonoPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bono $bono): bool
    {
        return $user->can(BonoPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(BonoPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bono $bono): bool
    {
        return $user->can(BonoPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bono $bono): bool
    {
        return $user->can(BonoPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bono $bono): bool
    {
        return $user->can(BonoPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bono $bono): bool
    {
        return $user->can(BonoPermissions::DELETE);
    }
}

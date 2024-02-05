<?php

namespace Modules\Inventories\Policies;

use Modules\Common\Permissions\ReasonPermissions;
use Modules\Inventories\Entities\Reason;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class ReasonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ReasonPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reason $reason): bool
    {
        return $user->can(ReasonPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(ReasonPermissions::CREATE);
    }

    public function import(User $user): bool
    {
        return $user->can(ReasonPermissions::IMPORT);
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reason $reason): bool
    {
        return $user->can(ReasonPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reason $reason): bool
    {
        return $user->can(ReasonPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reason $reason): bool
    {
        return $user->can(ReasonPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reason $reason): bool
    {
        return $user->can(ReasonPermissions::DELETE);
    }
}

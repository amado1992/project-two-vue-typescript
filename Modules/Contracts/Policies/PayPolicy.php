<?php

namespace Modules\Contracts\Policies;


use Modules\Common\Permissions\PayPermissions;
use Modules\Contracts\Entities\Pay;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class PayPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PayPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pay $pay): bool
    {
        return $user->can(PayPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PayPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pay $pay): bool
    {
        return $user->can(PayPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pay $pay): bool
    {
        return $user->can(PayPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pay $pay): bool
    {
        return $user->can(PayPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pay $pay): bool
    {
        return $user->can(PayPermissions::DELETE);
    }
}

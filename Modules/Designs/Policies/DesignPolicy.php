<?php

namespace Modules\Designs\Policies;

use Modules\Common\Permissions\DesignPermissions;
use Modules\Designs\Entities\Design;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class DesignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(DesignPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Design $design): bool
    {
        return $user->can(DesignPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(DesignPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Design $design): bool
    {
        return $user->can(DesignPermissions::UPDATE) &&
            ! $design->quote->contract_id;
    }

    /**
     * Determine whether the user can approve the design.
     *
     * @param User $user
     * @param Design $design
     * @return bool
     */
    public function approve(User $user, Design $design): bool
    {
        return $user->can(DesignPermissions::APPROVE) &&
            ! $design->quote->approved;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Design $design): bool
    {
        return $user->can(DesignPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Design $design): bool
    {
        return $user->can(DesignPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Design $design): bool
    {
        return $user->can(DesignPermissions::DELETE);
    }
}

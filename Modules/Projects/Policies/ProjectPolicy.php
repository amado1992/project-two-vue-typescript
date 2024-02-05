<?php

namespace Modules\Projects\Policies;

use Modules\Common\Permissions\ProjectPermissions;
use Modules\Projects\Entities\Project;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ProjectPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->can(ProjectPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(ProjectPermissions::CREATE);
    }

    public function import(User $user): bool
    {
        return $user->can(ProjectPermissions::IMPORT);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->can(ProjectPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->can(ProjectPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->can(ProjectPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->can(ProjectPermissions::DELETE);
    }
}

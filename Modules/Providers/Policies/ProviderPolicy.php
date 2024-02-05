<?php

namespace Modules\Providers\Policies;

use Modules\Common\Permissions\ProviderPermissions;
use Modules\Providers\Entities\Provider;
use Modules\Users\Entities\User;

class ProviderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ProviderPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Provider $provider): bool
    {
        return $user->can(ProviderPermissions::READ);
    }

    public function import(User $user): bool
    {
        return $user->can(ProviderPermissions::IMPORT);
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(ProviderPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Provider $provider): bool
    {
        return $user->can(ProviderPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Provider $provider): bool
    {
        return $user->can(ProviderPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Provider $provider): bool
    {
        return $user->can(ProviderPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Provider $provider): bool
    {
        return $user->can(ProviderPermissions::DELETE);
    }
}

<?php

namespace Modules\Products\Policies;

use Modules\Products\Entities\Product;
use Modules\Users\Entities\User;
use Modules\Users\Policies\ProductPermissions;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ProductPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->can(ProductPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(ProductPermissions::CREATE);
    }

    public function import(User $user): bool
    {
        return $user->can(ProductPermissions::IMPORT);
    }    
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->can(ProductPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->can(ProductPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->can(ProductPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->can(ProductPermissions::DELETE);
    }
}

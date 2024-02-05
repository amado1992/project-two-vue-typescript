<?php

namespace Modules\ProductCategories\Policies;

use Modules\Common\Permissions\ProductCategoryPermissions;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\Users\Entities\User;

class ProductCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ProductCategoryPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductCategory $productCategory): bool
    {
        return $user->can(ProductCategoryPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(ProductCategoryPermissions::CREATE);
    }

    public function import(User $user): bool
    {
        return $user->can(ProductCategoryPermissions::IMPORT);
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductCategory $productCategory): bool
    {
        return $user->can(ProductCategoryPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can(ProductCategoryPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductCategory $productCategory): bool
    {
        return $user->can(ProductCategoryPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductCategory $productCategory): bool
    {
        return $user->can(ProductCategoryPermissions::DELETE);
    }
}

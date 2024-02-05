<?php

namespace Modules\ReRents\Policies;

use Modules\Common\Permissions\ContractPermissions;
use Modules\Common\Permissions\ReRentPermissions;
use Modules\Contracts\Entities\Contract;
use Modules\ReRents\Entities\ReRent;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class ReRentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ReRentPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::READ);
    }

    /**
     * Determine whether the user can finish the model.
     *
     * @param User $user
     * @param ReRent $reRent
     * @return bool
     */
    public function finish(User $user, ReRent $reRent): bool
    {
        if ($reRent->finished_at) {

            return false;
        }

        return $user->can(ReRentPermissions::UPDATE) &&
            $this->canFinish($reRent);
    }

    /**
     * Determine whether the user can cancel the model.
     *
     * @param User $user
     * @param ReRent $reRent
     * @return bool
     */
    public function cancel(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::UPDATE) &&
            ! $reRent->cancelled_at &&
            ! $reRent->finished_at;
    }

    /**
     * Determine whether the user can add returns to the reRent.
     *
     * @param User $user
     * @param ReRent $reRent
     * @return bool
     */
    public function returns(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::READ);
    }

    /**
     * Determine whether the user can add a return to the reRent.
     *
     * @param User $user
     * @param ReRent $reRent
     * @return bool
     */
    public function addReturns(User $user, ReRent $reRent): bool
    {
        if ($reRent->finished_at) {

            return false;
        }

        return $user->can(ReRentPermissions::UPDATE) &&
            ! $this->canFinish($reRent);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(ReRentPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::DELETE);
    }

    public function start(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::UPDATE) &&
            ! $reRent->active_at &&
            ! $reRent->cancelled_at;
    }

    public function edit(User $user, ReRent $reRent): bool
    {
        return $user->can(ReRentPermissions::UPDATE) &&
            ! $reRent->active_at;
    }


    /**
     * @param ReRent $reRent
     * @return bool
     */
    private function canFinish(ReRent $reRent): bool
    {
        foreach ($reRent->products as $product) {

            if ($product->pivot->quantity > $product->pivot->returned) {

                return false;
            }
        }

        return true;
    }
}

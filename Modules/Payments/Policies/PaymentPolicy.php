<?php

namespace Modules\Payments\Policies;

use Modules\Common\Permissions\PaymentPermissions;
use Modules\Payments\Entities\Payment;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class PaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PaymentPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->can(PaymentPermissions::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PaymentPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        return false;
        //return $user->can(PaymentPermissions::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $user->can(PaymentPermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return $user->can(PaymentPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->can(PaymentPermissions::DELETE);
    }

    /**
     * Determine whether the user can details the model.
     */
    public function details(User $user, Payment $payment): bool
    {
        return $user->can(PaymentPermissions::DETAILS);
    }
}

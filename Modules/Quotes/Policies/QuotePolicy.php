<?php

namespace Modules\Quotes\Policies;

use Modules\Common\Permissions\QuotePermissions;
use Modules\Quotes\Entities\Quote;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class QuotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(QuotePermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quote $quote): bool
    {
        return $user->can(QuotePermissions::READ);
        /* return $user->client_id === $quote->client_id; */
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(QuotePermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quote $quote): bool
    {
        return $user->can(QuotePermissions::UPDATE) && ! $quote->contract_id;
    }

    /**
     * Determine whether the user can approve the quote.
     *
     * @param User $user
     * @param Quote $quote
     * @return bool
     */
    public function approve(User $user, Quote $quote): bool
    {
        return $user->can(QuotePermissions::APPROVE) && ! $quote->approved;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quote $quote): bool
    {
        return $user->can(QuotePermissions::DELETE);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Quote $quote): bool
    {
        return $user->can(QuotePermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Quote $quote): bool
    {
        return $user->can(QuotePermissions::DELETE);
    }
}

<?php

namespace Modules\Contracts\Policies;

use Modules\Common\Permissions\ContractPermissions;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\ContractProduct;
use Modules\Products\Entities\Product;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class ContractPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ContractPermissions::READ);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::READ);
    }

    /**
     * Determine whether the user can add returns to the contract.
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function returns(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::READ);
    }

    /**
     * Determine whether the user can add a return to the contract.
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function addReturns(User $user, Contract $contract): bool
    {
        if ($contract->finished_at || $contract->cancelled_at) {

            return false;
        }

        $canAddReturn = ! $this->ensureCanBeFinish($contract);

        return $user->can(ContractPermissions::UPDATE) && $canAddReturn;
    }

    /**
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function renovations(User $user, Contract $contract): bool
    {
        $is_not_pending = $contract->status != Contract::PENDING_STATUS;

        return $user->can(ContractPermissions::READ) && $is_not_pending;
    }

    /**
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function addRenovations(User $user, Contract $contract): bool
    {
        /*$is_not_active = $contract->status != Contract::ACTIVE_STATUS;
        $is_not_finished = $contract->status != Contract::FINISHED_STATUS;
        $is_not_cancelled = $contract->status != Contract::CANCELLED_STATUS;

        return $user->can(ContractPermissions::UPDATE) &&
            $is_not_active &&
            $is_not_finished &&
            $is_not_cancelled;*/

            return $user->can(ContractPermissions::UPDATE) &&
            $contract->status == Contract::DEFEATED_STATUS &&
            !$this->ensureCanBeFinish($contract);
    }

    /**
     * Determine whether the user can start the contract.
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function start(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::UPDATE) &&
            ! $contract->active_at &&
            ! $contract->cancelled_at;
    }

    /**
     * Determine whether the user can finish the contract.
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function finish(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::UPDATE) &&
            $contract->active_at &&
            ! $contract->finished_at &&
            $this->ensureCanBeFinish($contract) &&
            ! $contract->cancelled_at;
    }

    /**
     * determine whether the user can cancel the contract.
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function cancel(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::CANCEL) &&
            ! $contract->cancelled_at &&
            ! $contract->finished_at;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(ContractPermissions::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::UPDATE) &&
            ! $contract->cancelled_at &&
            ! $contract->active_at;
    }

    /**
     * Determine whether the user can update the contract's date.
     *
     * @param User $user
     * @param Contract $contract
     * @return bool
     */
    public function updateDate(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::UPDATE_DATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::DELETE) && ! $contract->active_at;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::CREATE);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contract $contract): bool
    {
        return $user->can(ContractPermissions::DELETE);
    }

    /**
     * @param Contract $contract
     * @return bool
     */
    private function ensureCanBeFinish(Contract $contract): bool
    {
        $canFinishResult = ContractProduct::query()
        ->where("contract_id","=",$contract->id)
        ->whereColumn("mesu_delivered",">","mesu_return",boolean:"or")
        ->whereColumn("re_rent_delivered",">","re_rent_return",boolean:"or")->get(['id']);


         if($canFinishResult->isNotEmpty()){
             return false;
         }

        return true;
    }
}

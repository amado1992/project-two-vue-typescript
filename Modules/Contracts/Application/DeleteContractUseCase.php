<?php

namespace Modules\Contracts\Application;

use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Events\ContractDeleted;
use Modules\Contracts\Events\DeletingContract;
use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class DeleteContractUseCase
{
    /**
     * @param Contract $contract
     * @return bool
     */
    public function __invoke(Contract $contract): bool
    {
        if ($contract->active_at) {

            return false;
        }

        DeletingContract::dispatch($contract);
        $id = $contract->id;
        $value = $contract->delete();
        if ($value) {

            ContractDeleted::dispatch($contract);
            Quote::query()->where(
                "contract_id","=",$id
            )->update(['contract_id' => null]);

            return true;
        }

        return false;
    }
}

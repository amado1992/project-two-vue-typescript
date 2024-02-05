<?php

namespace Modules\Contracts\Application;

use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Events\ContractUpdated;
use Modules\Contracts\Events\UpdatingContract;

/**
 * @author Abel David.
 */
class UpdateDateContractUseCase
{
    /**
     * @param string $date
     * @param Contract $contract
     * @return bool
     */
    public function __invoke(string $date, Contract $contract): bool
    {
        $contract->fill([
            'date' => $date
        ]);

        if ($contract->isDirty()) {

            UpdatingContract::dispatch($contract);
        }

        $contract->save();

        $wasChanged = $contract->wasChanged();

        if ($wasChanged) {

            ContractUpdated::dispatch($contract);
        }

        return $wasChanged;
    }
}

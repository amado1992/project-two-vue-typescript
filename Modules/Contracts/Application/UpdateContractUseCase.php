<?php

namespace Modules\Contracts\Application;

use Modules\Common\Application\WithContractibleProducts;
use Modules\Common\Permissions\ContractPermissions;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Events\ContractUpdated;
use Modules\Contracts\Events\UpdatingContract;

/**
 * @author Abel David.
 */
class UpdateContractUseCase
{
    use WithContractibleProducts;

    /**
     * @param array $data
     * @param Contract $contract
     * @return bool
     */
    public function __invoke(array $data, Contract $contract): bool
    {
        $oldContract = clone $contract;

        $this->filterDate($data);

        $contract->fill($data);
        $wasChanged = $this->syncContractibleProducts($contract, $data['products'], $data['tax_exempt']);

        if ($contract->isDirty() || $wasChanged) {

            $contract->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingContract::dispatch($contract);
        }

        $contract->save();

        $wasChanged = $contract->wasChanged() || $wasChanged;

        if ($wasChanged) {

            ContractUpdated::dispatch($contract, $oldContract);
        }

        return $wasChanged;
    }

    /**
     * @param $data
     * @return void
     */
    private function filterDate($data): void
    {
        if (isset($data['date']) && ! $this->canUpdateDate()) {

            unset($data['date']);
        }
    }

    /**
     * @return bool
     */
    private function canUpdateDate(): bool
    {
        return auth()->user()->can(ContractPermissions::UPDATE_DATE);
    }
}

<?php

namespace Modules\Contracts\Application;

use Exception;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Events\ContractFinished;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class FinishContractUseCase
{
    /**
     * @param Contract $contract
     * @return void
     * @throws Exception
     */
    public function __invoke(Contract $contract): void
    {
        if ($contract->finished_at) {

            throw new Exception(__('The contract is already finished.'));
        }

        $this->ensureCanBeFinish($contract);

        $contract->fill([
            'finished_at' => now(),
            'finished_by' => auth()->user()->getAuthIdentifier()
        ])->save();

        ContractFinished::dispatch($contract);
    }

    /**
     * @param Contract $contract
     * @return void
     * @throws Exception
     */
    private function ensureCanBeFinish(Contract $contract): void
    {
        $contract->products->each(function (Product $product) {

            if ($product->pivot->mesu_delivered < $product->pivot->mesu_return) {

                throw new Exception(__('The contract cannot be finalized because it still has unreturned products.'));
            }

            if ($product->pivot->re_rent_delivered < $product->pivot->re_rent_return) {

                throw new Exception(__('The contract cannot be finalized because it still has unreturned products.'));
            }
        });
    }
}

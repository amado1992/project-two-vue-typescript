<?php

namespace Modules\Contracts\Application;

use Modules\Common\Application\WithContractibleProducts;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Events\ContractCreated;
use Modules\Contracts\Events\CreatingContract;
use Modules\Quotes\Entities\Quote;

/**
 * @author Abel David.
 */
class CreateContractUseCase
{
    use WithContractibleProducts;

    /**
     * @param array $data
     * @return Contract
     */
    public function __invoke(array $data): Contract
    {
        CreatingContract::dispatch();

        $data['created_by'] = auth()->user()->getAuthIdentifier();
        $contract = Contract::create($data);

        $this->syncContractibleProducts($contract, $data['products'], $data['tax_exempt']);

        if (isset($data['quote_id'])) {

            if ($quote = Quote::find($data['quote_id'])) {

                $quote->fill([
                    'contract_id' => $contract->id
                ])->save();
            }
        }

        ContractCreated::dispatch($contract);

        return $contract;
    }
}

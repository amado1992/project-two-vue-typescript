<?php

namespace Modules\Contracts\Application;

use Exception;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Events\ContractCancelled;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class CancelContractUseCase
{
    /**
     * @param Contract $contract
     * @param string|null $reason
     * @return void
     * @throws Exception
     */
    public function __invoke(Contract $contract, ?string $reason): void
    {
        if ($contract->cancelled_at) {

            throw new Exception('The contract is already cancelled.');
        }

        if ($contract->finished_at) {

            throw new Exception('The contract can\'t cancel because is already finished.');
        }

        $contract->fill([
            'cancelled_at' => now(),
            'cancelled_by' => auth()->user()->getAuthIdentifier(),
            'cancelled_reason' => $reason
        ])->save();


        if ($contract->active_at) {

            foreach ($contract->products as $product) {
            
                $productModel = Product::findOrFail($product->id);

                $mesu = $product->pivot->mesu_delivered - $product->pivot->mesu_return;
            
                $productModel->inventory->fill([
                    'stock' => $productModel->inventory->stock + $mesu,
                    'rented' => $productModel->inventory->rented - $mesu,
                    're_stock' => $productModel->inventory->re_stock + $product->pivot->re_rent_delivered,
                    're_rented' => $productModel->inventory->re_rented - $product->pivot->re_rent_delivered
                ])->save();
            };

        }

        ContractCancelled::dispatch($contract);
    }
}

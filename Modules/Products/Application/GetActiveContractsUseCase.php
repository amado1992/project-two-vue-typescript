<?php

namespace Modules\Products\Application;

use Illuminate\Support\Collection;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\ContractProduct;
use Modules\Contracts\Entities\ContractReturnProduct;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class GetActiveContractsUseCase
{
    /**
     * @param Product $product
     * @return Collection
     */
    public function __invoke(Product $product): Collection
    {
        return ContractProduct::query()
            ->with('contract')
            ->where('product_id', $product->id)
            ->get()
            ->filter(function (ContractProduct $contractProduct) {
                return $contractProduct->contract->status == Contract::ACTIVE_STATUS;
            })
            ->map(function (ContractProduct $contractProduct) {

                $returnProducts = ContractReturnProduct::query()
                    ->join('contract_returns', 'contract_returns.id', 'contract_return_product.contract_return_id')
                    ->where('contract_returns.contract_id', $contractProduct->contract_id)
                    ->where('contract_return_product.product_id', $contractProduct->product_id)
                    ->select('contract_return_product.*')
                    ->get();

                $returnProducts->each(function (ContractReturnProduct $contractReturnProduct) use ($contractProduct) {

                    $contractProduct->quantity -= $contractReturnProduct->mesu_return + $contractReturnProduct->re_rent_return;
                });

                $contractProduct->subtotal = $contractProduct->price * $contractProduct->quantity - $contractProduct->discount;
                $contractProduct->total = $contractProduct->subtotal + $contractProduct->tax;

                return $contractProduct;
            });
    }
}
